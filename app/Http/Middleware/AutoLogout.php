<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Import the Log facade
use App\Models\Login;
use Carbon\Carbon;

class AutoLogout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            Log::info('User is authenticated.');

            // Update session's last activity time on each request
            $request->session()->put('last_activity_time', now());

            // Log the session's last activity time
            Log::info('Updated last activity time: ' . now());

            // Check if the session has expired due to inactivity
            if ($this->isSessionExpired($request)) {
                Log::info('Session has expired due to inactivity.');

                // Get the authenticated user
                $user = Auth::user();

                // Get the last login record for this user and session ID
                $lastLogin = Login::where('employee_id', $user->employee_id)
                    ->where('session_id', $request->session()->getId())
                    ->latest()
                    ->first();

                // Update the logout time if the login record exists
                if ($lastLogin) {
                    Log::info('Updating logout time for session.');
                    $lastLogin->update(['logout_time' => now()]);
                }

                // Logout the user and invalidate the session
                Auth::logout();
                Log::info('User has been logged out due to inactivity.');

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Log::info('Session has been invalidated and token regenerated.');

                // Redirect the user to the login page with a message
                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }
        }

        return $next($request);
    }

    /**
     * Check if the session has expired.
     */
    private function isSessionExpired(Request $request): bool
    {
        Log::info('Checking if the session has expired.');

        // Retrieve the last activity time from the session
        $lastActivity = $request->session()->get('last_activity_time');
        $sessionLifetime = config('session.lifetime'); // Session lifetime from config (in minutes)

        // Log session lifetime
        Log::info('Session lifetime is: ' . $sessionLifetime . ' minutes.');

        // If there's no last activity time, the session is considered active
        if (!$lastActivity) {
            Log::info('No last activity time found. Session is still active.');
            return false;
        }

        // Log the difference between the last activity time and the current time
        $minutesInactive = now()->diffInMinutes(Carbon::parse($lastActivity));
        Log::info('User has been inactive for: ' . $minutesInactive . ' minutes.');

        // Check if the difference between current time and last activity time exceeds session lifetime
        return $minutesInactive >= $sessionLifetime;
    }
}
