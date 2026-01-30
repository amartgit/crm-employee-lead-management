<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\EmployeePermission;
 use App\Models\DeviceToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    
   

public function deviceTokens()
{
    $deviceTokens = DeviceToken::with('user')->orderBy('created_at', 'desc')->get();
    return view('admin.device_tokens', compact('deviceTokens'));
}

public function updateDeviceTokens(Request $request)
{
    $request->validate([
        'token_ids' => 'required|array',
        'action' => 'required|string|in:approve,reject,delete',
    ]);

    $tokens = DeviceToken::whereIn('id', $request->token_ids)->get();

    if ($request->action === 'delete') {
        foreach ($tokens as $token) {
            $token->delete();
        }
        return back()->with('success', 'Selected device tokens deleted successfully.');
    }

    foreach ($tokens as $token) {
        $token->is_verified = $request->action === 'approve';
        $token->save();
    }

    return back()->with('success', 'Device tokens updated successfully.');
}


    // Dashboard view for Super Admin
    public function index()
    {
        return view('admin.index');
    }

    // Show all employees
    // public function showEmployees()
    // {
    //     $employees = Employee::all(); // Retrieve all employees
    //     return view('admin.employees.index', compact('employees'));
    // }
    

public function showEmployees()
    {
        $employees = Employee::all(); // Retrieve all employees

        // Query the sessions table to check for active sessions (you may need to adjust based on your session config)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>', time() - 1800) // Check for sessions active within the last 30 minutes
            ->pluck('user_id')
            ->toArray();

        return view('admin.employees.index', compact('employees', 'activeSessions'));
    }

    // Show the form to edit an employee's details
    public function editEmployee(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    // Update an employee's details
    public function updateEmployee(Request $request, Employee $employee)
    {
        // Validate incoming request
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone_number' =>
            [
                'required',
                'string',
                'regex:/^\d{10}$/', // Ensure it's exactly 10 digits
                'unique:users,phone_number,' . $employee->user->id,
            ],
            'whatsapp_number' =>
            [
                'nullable',
                'string',
                'regex:/^\d{10}$/', // Ensure it's exactly 10 digits
                'unique:users,phone_number,' . $employee->user->id,
            ],
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'mailid' => 'nullable|email|unique:employees,mailid,' . $employee->id,
            'address' => 'nullable|string|max:500',
        ],
        [
            // Custom error messages
            'fname.required' => 'The first name is required.',

            'lname.required' => 'The last name is required.',

            'phone_number.required' => 'The phone number is required.',
            'phone_number.regex' => 'The phone number must be exactly 10 digits and contain only numbers.',
            'phone_number.unique' => 'The phone number has already been taken by another user.',

            'whatsapp_number.regex' => 'The phone number must be exactly 10 digits and contain only numbers.',
        ]);

        // Update Employee record
        $employee->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'mailid' => $request->mailid,
            'address' => $request->address,
        ]);

        // Update associated User record if needed
        if ($employee->user) {
            $employee->user->update([
                'phone_number' => $request->phone_number,
                'name' => $request->fname . '' . $request->lname,

            ]);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    // Delete an employee
    public function deleteEmployee(Employee $employee)
    {
        // Soft delete the associated User record if it exists
        if ($employee->user) {
            $employee->user->delete(); // This will set the 'deleted_at' timestamp
        }

        // Soft delete the Employee record
        $employee->delete(); // This will set the 'deleted_at' timestamp for the employee

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully!');
    }


    // Fetch employee details (used for modal display)
    public function getEmployeeDetails($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail(); // Fetch by employee_id
        return response()->json(['employee' => $employee]);
    }
    
    

    // Show edit form for a given user (using employee's employee_id)
    public function editRoleDep($employee_id)
    {
        // Retrieve the employee and their associated user
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
        
        // Return the view with the employee data (you'll need to create a view for this form)
        return view('admin.employees.edit_role_dep', compact('employee'));
    }

    // Update action to change the role and department
 public function updateRoleDep(Request $request, $employee_id)
{
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Prevent Superadmin from being edited
    if ($employee->user->role === 'SuperAdmin') {
        return redirect()->back()->with('error', 'SuperAdmin role and department cannot be changed.');
    }

    // Validate request data
    $validatedData = $request->validate([
        'role' => 'required|in:Admin,Employee,SuperAdmin', // Allowed roles
        'department' => 'required|in:HR,IT,Sales,Manager,User,Finance - Accounts,Production,Designer' // Allowed departments
    ]);

    // Update role and department
    $employee->user->role = $validatedData['role'];
    $employee->department = $validatedData['department'];
    $employee->user->save();
    $employee->save();

    return redirect()->route('admin.employees.index')->with('success', 'Role and Department updated successfully.');
}

  // Show edit form for permissions of a given employee (using employee_id)
    public function editPermissions($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        // Assuming 'permissions' is a relation in the Employee model
        $permissions = $employee->permissions()->get(); // Fetch existing permissions

        // List of available features for permissions (can be customized)
        $features = ['sales', 'attendance'];

        return view('admin.employees.edit_permissions', compact('employee', 'permissions', 'features'));
    }
    
public function updatePermissions(Request $request, $employee_id)
{
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Safely get features from request, default to empty array if none selected
    $features = $request->input('permissions', []);

    // Optional: Validate only if array is not empty
    if (!empty($features)) {
        $request->validate([
            'permissions.*' => 'in:sales,attendance',
        ]);
    }

    // Remove old permissions
    $employee->permissions()->delete();

    // Assign new permissions
    foreach ($features as $feature) {
        $employee->permissions()->create([
            'feature' => $feature,
            'allowed' => true,
        ]);
    }

    return redirect()->route('admin.employees.index')->with('success', 'Permissions updated successfully.');
}

}
