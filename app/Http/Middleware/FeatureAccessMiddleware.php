<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FeatureAccessMiddleware
{
    public function handle($request, Closure $next, $feature)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect('/login')->with('error', 'Please login to access this feature.');
        }

        // Access the employee associated with the user (via relationship in User model)
        $employee = $user->employee;

        // Check permission only if employee is found
        if ($employee && $employee->employee_id) {
            $hasPermission = $employee->permissions()
                ->where('feature', $feature)
                ->where('allowed', true)
                ->exists();

            if ($hasPermission) {
                return $next($request); 
            }
        }

        // 🔒 Permission denied: Redirect based on role
        $redirectPaths = [
            'SuperAdmin' => '/superadmin',
            'Admin'      => '/admin',
            'Employee'   => '/employee',
        ];

        $role = $user->role ?? 'Guest';
        $redirectTo = $redirectPaths[$role] ?? '/';

        return redirect($redirectTo)->with('error', 'Access denied to feature: ' . $feature);
    }
}
