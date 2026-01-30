<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $lastEmployee = Employee::withTrashed()
        ->where('employee_id', 'LIKE', 'VI%')
        ->orderBy('employee_id', 'DESC')
        ->first();

        if ($lastEmployee) {
            $newEmployeeNumber = (int)str_replace('VI', '', $lastEmployee->employee_id) + 1;
        } else {
            $newEmployeeNumber = 1; // First employee ID
        }

        $newEmployeeId = 'VI' . str_pad($newEmployeeNumber, 3, '0', STR_PAD_LEFT); // Generate VI001, VI002, etc.

        return view('auth.register', compact('newEmployeeId'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:users', // Ensure unique employee_id
            'phone_number' => [
                'required',
                'string',
                'regex:/^\d{10}$/', // Ensure it's exactly 10 digits
                'unique:users,phone_number,', // Ensure the phone number is unique for the user
            ],
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
        ],
        [
            // Custom error messages
            'fname.required' => 'The first name is required.',

            'lname.required' => 'The last name is required.',

            'phone_number.required' => 'The phone number is required.',
            'phone_number.regex' => 'The phone number must be exactly 10 digits and contain only numbers.',
            'phone_number.unique' => 'The phone number has already been taken by another user.',
        ]);

        // Create the user record
        $user = User::create([
            'name' => $request->fname . ' ' . $request->lname,
            'employee_id' => $request->employee_id,
            'phone_number' => $request->phone_number,
            'password' => bcrypt('123456'), // Automatically set default password
            'role' => 'Employee', // Default role
        ]);

        // Create the employee record
        Employee::create([
            'employee_id' => $user->employee_id,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone_number' => $request->phone_number,
        ]);


        return redirect()->route('admin.employees.index')->with('success', 'Employee registered successfully!');
}
}
