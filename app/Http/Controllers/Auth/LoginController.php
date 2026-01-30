<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Login;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerification;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Redirect authenticated users based on their role
            $role = Auth::user()->role;
            switch ($role) {
                case 'SuperAdmin':
                    return redirect('/superadmin');
                case 'Admin':
                    return redirect('/admin');
                case 'Employee':
                    return redirect('/employee');
                default:
                    return redirect('/');
            }
        }

        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|string|exists:users,employee_id',
    //         'password' => 'required|string',
    //     ]);

    //     $credentials = $request->only('employee_id', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
            
    //         //  Delete all previous session rows from 'sessions' table for this user
    //     $userId = $user->id;

    //     DB::table('sessions')->where('user_id', $userId)->delete();

    //     //  Mark any open login sessions as logged out
    //     Login::where('employee_id', $user->employee_id)
    //         ->whereNull('logout_time')
    //         ->update(['logout_time' => now()]);

    //         $request->session()->regenerate();

    //         // Store login details
    //         Login::create([
    //             'employee_id' => $user->employee_id,
    //             'login_time' => now(),
    //             'ip_address' => $request->ip(),
    //             'session_id' => $request->session()->getId(), // Store session ID

    //         ]);

    //         // Redirect based on role
    //         $role = Auth::user()->role;
    //         switch ($role) {
    //             case 'SuperAdmin':
    //                 return redirect()->intended('/superadmin');
    //             case 'Admin':
    //                 return redirect()->intended('/admin');
    //             case 'Employee':
    //                 return redirect()->intended('/employee');
    //             default:
    //                 return redirect()->intended('/');
    //         }
    //     }

    //     return back()->withErrors([
    //         'employee_id' => __('The provided credentials do not match our records.'),
    //     ]);
    // }

public function login(Request $request)
{
    try {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
            'device_token' => 'nullable|string'
        ]);

        // Validate credentials
        $credentials = $request->only('employee_id', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::info("Login successful for employee_id: {$user->employee_id}");

            // Check if the user is from the Sales department
            if ((optional($user->employee)->department) === 'Sales') {
                $deviceToken = $request->device_token;
                Log::info("Checking device token for user: {$user->employee_id}, token: {$deviceToken}");

                // Check if the device is already verified
                $knownDevice = DeviceToken::where('user_id', $user->id)
                    ->where('device_token', $deviceToken)
                    ->where('is_verified', true)
                    ->first();

                if (!$knownDevice) {
                    Log::info("Device token not verified for user: {$user->employee_id}, generating OTP.");

                    // Generate OTP and store it in the session
                    $otp = rand(100000, 999999);
                    Log::info("Generated OTP: {$otp}");
                    $expiryTime = now()->addMinutes(10);

                    // Store OTP and other details in session
                    session([
                        'otp' => $otp,
                        'pending_user_id' => $user->id,
                        'pending_device_token' => $deviceToken,
                        'otp_expiry_time' => $expiryTime, // Expiry time for OTP
                    ]);

                    // Send OTP to admin email
                    // Mail::to('amar01kit@gmail.com','preetam.vibrantideas@gmail.com')->send(new OtpVerification($otp, $user));
                    // Send OTP to admin email and second email
                    $emails = ['preetam.vibrantideas@gmail.com', 'mitalipandey319@gmail.com', 'siddhesh.vibrantideas@gmail.com ']; // Add the second email here
                    foreach ($emails as $email) {
                        Mail::to($email)->send(new OtpVerification($otp, $user));
                        Log::info("OTP sent to email: {$email} for employee_id: {$user->employee_id}");
                    }

                    Log::info("OTP sent to admin email for employee_id: {$user->employee_id}");

                    // Logout the user
                    Auth::logout();
                    Log::info("User logged out and redirected to OTP verification page.");

                    return redirect()->route('verify.otp.form')->with('message', 'OTP sent to admin. Please enter OTP to verify device.');
                } else {
                    Log::info("Verified device token for employee_id: {$user->employee_id}. Proceeding to dashboard.");
                }
            }

            // Redirect based on user's role
            $role = Auth::user()->role;
            switch ($role) {
                case 'SuperAdmin':
                    return redirect()->intended('/superadmin');
                case 'Admin':
                    return redirect()->intended('/admin');
                case 'Employee':
                    return redirect()->intended('/employee');
                default:
                    return redirect()->intended('/');
            }
        }

        // If authentication fails
        Log::warning("Login failed for employee_id: {$request->employee_id}. Invalid credentials.");
        return back()->withErrors(['employee_id' => 'Invalid credentials.']);
    } catch (\Throwable $e) {
        Log::error("Exception during login: " . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong during login. Please try again.']);
    }
}



public function verifyOtp(Request $request)
{
    try {
        $request->validate(['otp' => 'required']);
        $pendingUserId = session('pending_user_id');

        // Initialize OTP attempts counter if it doesn't exist
        if (!session()->has('otp_attempts')) {
            session(['otp_attempts' => 0]);
        }

        Log::info("OTP verification attempt for user ID: {$pendingUserId}");

        // Check if the OTP has expired
        $expiryTime = session('otp_expiry_time');
        if ($expiryTime && now()->gt($expiryTime)) {
            Log::warning("OTP expired for user ID: {$pendingUserId}. Redirecting to login.");

            // Log out the user and end the session if OTP expired
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/login')->withErrors(['otp' => 'OTP has expired. Please try logging in again.']);
        }

        // Check if the OTP matches
        if ($request->otp == session('otp')) {
            Log::info("OTP matched for user ID: {$pendingUserId}");

            $deviceToken = session('pending_device_token');

            // Save the device token as verified
            // DeviceToken::create([
            //     'user_id' => $pendingUserId,
            //     'device_token' => $deviceToken,
            //     'is_verified' => true,
            // ]);
            
            $existingToken = DeviceToken::where('user_id', $pendingUserId)
    ->where('device_token', $deviceToken)
    ->first();

if ($existingToken) {
    if (!$existingToken->is_verified) {
        $existingToken->is_verified = true;
        $existingToken->save();
        Log::info("Existing device token verified for user ID: {$pendingUserId}");
    } else {
        Log::info("Device token already verified for user ID: {$pendingUserId}");
    }
} else {
    DeviceToken::create([
        'user_id' => $pendingUserId,
        'device_token' => $deviceToken,
        'is_verified' => true,
    ]);
    Log::info("New device token saved for user ID: {$pendingUserId}");
}


            // Login the user
            Auth::loginUsingId($pendingUserId);

            // Reset session data
            session()->forget(['otp', 'pending_user_id', 'pending_device_token', 'otp_expiry_time', 'otp_attempts']);

            Log::info("User ID {$pendingUserId} logged in after OTP verification.");

            return redirect('/employee')->with('success', 'Device verified & login successful.');
        } else {
            // Increment OTP attempts counter
            session(['otp_attempts' => session('otp_attempts') + 1]);

            // Check if max attempts reached (5 attempts in this case)
            if (session('otp_attempts') >= 5) {
                // Log out the user and end the session
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                Log::warning("Max OTP attempts exceeded for user ID: {$pendingUserId}. Redirecting to login page.");

                return redirect('/login')->withErrors(['otp' => 'Max OTP attempts exceeded. Please try logging in again.']);
            }

            Log::warning("Invalid OTP for user ID: {$pendingUserId}. Attempt " . session('otp_attempts') . " of 5.");
            return back()->withErrors(['otp' => 'Invalid OTP. You have ' . (5 - session('otp_attempts')) . ' attempts left.']);
        }
    } catch (\Throwable $e) {
        Log::error("Exception during OTP verification for user ID " . session('pending_user_id') . ": " . $e->getMessage());
        return back()->withErrors(['error' => 'Something went wrong during OTP verification. Please try again.']);
    }
}




    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

  


    // public function logout(Request $request)
    // {

    //     $user = Auth::user();

    //     // Get the latest login entry as an Eloquent model
    //     $lastLogin = Login::where('employee_id', $user->employee_id)
    //         ->latest()
    //         ->first();

    //     if ($lastLogin) {
    //         $lastLogin->update(['logout_time' => now()]);
    //     }
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/login');
    // }
    
     public function logout(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

         // Get the latest login entry for the user based on the session ID
    $lastLogin = Login::where('employee_id', $user->employee_id)
        ->where('session_id', $request->session()->getId()) // Match session ID
        ->latest()
        ->first();

    if ($lastLogin) {
        // Update the logout time for the session
        $lastLogin->update(['logout_time' => now()]);
    }

        // Log out the user and invalidate the session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect('/login')->with('message', 'You have been logged out.');
    }


 public function showLogins(Request $request)
{
    $query = Login::query();

    // Apply filters
    if ($request->filled('employee_id')) {
        $query->where('employee_id', trim($request->employee_id));
    }

    if ($request->filled('date')) {
        $query->whereDate('login_time', Carbon::parse($request->date));
    }

    if ($request->filled('month')) {
        $query->whereMonth('login_time', $request->month);
    }
    
    // Eager load the 'user' relationship to fetch employee names efficiently
    $logins = $query->with('employee')  // Eager load the user relation
                    ->orderByDesc('login_time')
                    ->paginate(10)
                    ->appends($request->query()); // Persist filters

    // Fetch filtered login records with pagination (ensuring filters persist)
    //$logins = $query->orderByDesc('login_time')->paginate(10)->appends($request->query());

    return view('admin.logins', compact('logins'));
}

public function dumm()
    {
        // Return the view for the dummy page
        return view('dummy');
    }

}
