<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Verify Employee ID and Mobile Number
    public function verifyUser(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,employee_id',
            'phone_number' => 'required|digits:10|exists:users,phone_number',
        ]);

        $user = User::where('employee_id', $request->employee_id)
                    ->where('phone_number', $request->phone_number)
                    ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Invalid Employee ID or Mobile Number']);
        }

        return view('auth.reset-password', ['employee_id' => $request->employee_id]);
    }
    
public function updatePassworduser(Request $request)
{
    // Validate the password and confirm password
    $request->validate([
        'employee_id' => 'required|exists:users,employee_id',
        'password' => 'required|string|min:6|confirmed', // 'confirmed' rule ensures password and password_confirmation match
    ]);

    // Check if the passwords match
    if ($request->password !== $request->password_confirmation) {
        return back()->withErrors(['password' => 'Passwords do not match.']);
    }

    // Find the user by employee_id
    $user = User::where('employee_id', $request->employee_id)->first();

    if ($user) {
        // Hash the new password and save it
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Password has been updated successfully!');
    }

    // If user not found or something went wrong
    return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
}

}
