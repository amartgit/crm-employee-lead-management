<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\AllowedIp;

class CheckApprovedIP
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $ip = $request->ip();

        $user = Auth::user();
        $ip = $request->ip();

        // Allow SuperAdmin to bypass IP check
        // if ($user && $user->role === 'SuperAdmin') {
        //     return $next($request);
        // }

        $allowed = AllowedIp::where('ip_address', $ip)->where('approved', true)->exists();

        if (!$allowed) {
            switch ($user->role) {
                case 'SuperAdmin':
                    return redirect('/superadmin')->with('error', 'Access denied: IP ' . $ip . ' is not approved for this page. Take approval from admin.');
                case 'Admin':
                    return redirect('/admin')->with('error', 'Access denied: IP ' . $ip . ' is not approved for this page. Take approval from admin.');
                case 'Employee':
                    return redirect('/employee')->with('error', 'Access denied: IP ' . $ip . ' is not approved for this page. Take approval from admin.');
                default:
                    return redirect('/')->with('error', 'Access denied: IP ' . $ip . ' is not approved for this page. Take approval from admin.');
            }
        }

        return $next($request);
    }
}
