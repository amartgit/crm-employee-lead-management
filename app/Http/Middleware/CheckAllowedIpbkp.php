<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class CheckAllowedIp
{
    public function handle(Request $request, Closure $next)
{
    $allowedIps = [
        '192.168.1.102','103.240.9.223', '192.168.1.19', '192.168.1.11', '192.168.1.10', '192.168.1.12', '192.168.1.151', '192.168.1.3', '192.168.1.7', '192.168.1.32', '192.168.0.104', '192.168.0.108', '103.240.9.199',
        '192.168.0.105', '192.168.0.118', '103.240.9.216'
    ];
    
        \Log::info('Client IP:', ['ip' => $request->ip()]);

    if (!in_array($request->ip(), $allowedIps)) {
            return redirect()->route('unauthorized');  // Redirect to the unauthorized route
    }
    
    return $next($request);

    //   $startRange = ip2long('192.168.0.101');
    //     $endRange = ip2long('192.168.0.120');
    //     $clientIpLong = ip2long($request->ip());

    //     // Check if the client IP is within the allowed range or specific IPs
    //     if (!in_array($request->ip(), $allowedIps) && !($clientIpLong >= $startRange && $clientIpLong <= $endRange)) {
    //         return redirect()->route('unauthorized');  // Redirect to the unauthorized route
    //     }

}

}