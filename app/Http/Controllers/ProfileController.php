<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;  

class ProfileController extends Controller
{
    // Show the profile page with the authenticated user's data
    public function showProfile()
    {
        $user = Auth::user(); // Retrieve the authenticated user
        $employee = $user->employee; // Retrieve the related employee model data via relationship

        return view('profile.index', compact('user', 'employee')); // Pass both user and employee data to the view
    }

    // Show the profile edit form
    public function editProfile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        return view('profile.edit', compact('user', 'employee'));
    }

    // Update the authenticated user's and related employee's profile data
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Fetch the authenticated user
        $employee = $user->employee; // Fetch the related employee record using the relationship

        // Validate input data
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'regex:/^\d{10}$/', // Ensure it's exactly 10 digits
                'unique:users,phone_number,' . $user->id, // Ensure the phone number is unique for the user
            ],
            'whatsapp_number' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/', // Ensure it's exactly 10 digits
            ],
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'mailid' => 'nullable|email|unique:employees,mailid,' . $employee->employee_id . ',employee_id|regex:/^[\w\.]+@([\w-]+\.)+[\w-]{2,4}$/',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'Emergency_contact' => 'nullable|string',

        ], [
            // Custom error messages
            'fname.required' => 'The first name is required.',

            'lname.required' => 'The last name is required.',

            'phone_number.required' => 'The phone number is required.',
            'phone_number.regex' => 'The phone number must be exactly 10 digits and contain only numbers.',
            'phone_number.unique' => 'The phone number has already been taken by another user.',

            'whatsapp_number.regex' => 'The WhatsApp number must be exactly 10 digits and contain only numbers.',

            'dob.date' => 'The date of birth must be a valid date.',


            'mailid.email' => 'The email address must be a valid email format.',
            'mailid.unique' => 'The email address has already been taken.',
            'mailid.regex' => 'The email address must follow the correct format (e.g., example@domain.com).',

        ]);

        // Update the employee model data
        $employee->fname = $request->fname; // Update first name
        $employee->mname = $request->mname; // Update middle name
        $employee->lname = $request->lname; // Update last name
        $employee->phone_number = $request->phone_number;
        $employee->whatsapp_number = $request->whatsapp_number;
        $employee->dob = $request->dob;
        $employee->gender = $request->gender;
        $employee->mailid = $request->mailid;
        $employee->address = $request->address;
        $employee->blood_group = $request->blood_group;
        $employee->Emergency_contact = $request->Emergency_contact;

        
        $employee->save(); // Save employee data (using Eloquent save())

        // Optionally update the user model (if needed)
        $user->name = $request->fname . ' ' . $request->lname; // Update full name (optional)
        $user->phone_number = $request->phone_number; // Update phone number if necessary
        $user->save(); // Save user data

        // Redirect back to profile page with success message
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
    
    
    // Show change password form
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }
    
// Update password
    public function updatePasswordemp(Request $request)
    {
        // Log the request data to see what is being submitted
        Log::info('Password update attempt:', $request->all());

        // Validate the current password and new password
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed', // minimum 6 characters and confirmation field
        ]);

        // If validation fails, redirect back with errors and old input
        if ($validator->fails()) {
            Log::error('Password validation failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        // Check if the current password matches the stored password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            Log::error('Incorrect current password for user:', ['user_id' => Auth::id()]);
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        // Log the password update attempt
        Log::info('Password updated for user:', ['user_id' => Auth::id()]);

        // Update the password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password); // Hash the new password
        $user->save();

        // Log success message
        Log::info('Password update successful:', ['user_id' => Auth::id()]);

        // Redirect back with success message
        return back()->with('success', 'Password updated successfully.');
    }

}
