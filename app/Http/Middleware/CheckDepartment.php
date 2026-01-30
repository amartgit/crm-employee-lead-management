<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class CheckDepartment
{
    public function handle(Request $request, Closure $next, $department)
    {
        if (Auth::check() && Auth::user()->role == 'Employee') {
            $employee = Employee::where('employee_id', Auth::user()->employee_id)->first();
            
            if ($employee && $employee->department === $department) {
                return $next($request);
            }
        }

        return abort(403, 'Unauthorized access');
    }
}