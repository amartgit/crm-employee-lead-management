<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SuperAdminController extends Controller
{
    // Dashboard view for Super Admin
    public function index()
    {
        return view('superadmin.index');
    }

    // Show all employees
    // public function showEmployees()
    // {
    //     $employees = Employee::all(); // Retrieve all employees
    //     return view('superadmin.employees.index', compact('employees'));
    // }


public function showEmployees()
    {
        $employees = Employee::all(); // Retrieve all employees

        // Query the sessions table to check for active sessions (you may need to adjust based on your session config)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>', time() - 1800) // Check for sessions active within the last 30 minutes
            ->pluck('user_id')
            ->toArray();

        return view('superadmin.employees.index', compact('employees', 'activeSessions'));
    }

    // Show the form to edit an employee's details
    public function editEmployee(Employee $employee)
    {
        return view('superadmin.employees.edit', compact('employee'));
    }

    // Update an employee's details
    public function updateEmployee(Request $request, Employee $employee)
    {
        // Validate incoming request
        $request->validate(
            [
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
            ]
        );

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

        if ($employee->user) {
            $employee->user->update([
                'phone_number' => $request->phone_number,
                'name' => $request->fname . '' . $request->lname,

            ]);
        }

        return redirect()->route('superadmin.employees.index')
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

        return redirect()->route('superadmin.employees.index')
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
        return view('superadmin.employees.edit_role_dep', compact('employee'));
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

    return redirect()->route('superadmin.employees.index')->with('success', 'Role and Department updated successfully.');
}

}
