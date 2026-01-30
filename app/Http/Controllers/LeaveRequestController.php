<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

// app/Http/Controllers/LeaveRequestController.php
class LeaveRequestController extends Controller
{
    public function create()
    {
        return view('leave_requests.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_type' => 'required|string|in:Casual,Sick,Paid,Unpaid',
            'reason' => 'nullable|string',
        ]);

        $employeeId = auth()->user()->id;

        // Check for overlapping leave requests
        $hasConflict = LeaveRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($hasConflict) {
            return redirect()->back()->withErrors([
                'leave_error' => 'You already have a leave request in this date range.'
            ]);
        }

        LeaveRequest::create([
            'employee_id' => $employeeId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully!');
    }


    public function index()
    {
        $leaveRequests = LeaveRequest::where('employee_id', auth()->user()->id)->get();
        return view('leave_requests.index', compact('leaveRequests'));
    }


    public function adminIndex(Request $request)
    {
        $employees = Employee::all();

        $query = LeaveRequest::with('employee')->latest();

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $leaveRequests = $query->paginate(20)->withQueryString();

        return view('admin.leave_requests.index', compact('leaveRequests', 'employees', 'request'));
    }


    // Approve a leave request
    public function approve($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'Approved';
        $leave->save();

        return redirect()->back()->with('success', 'Leave approved.');
    }

    // Reject a leave request
    public function reject($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'Rejected';
        $leave->save();

        return redirect()->back()->with('success', 'Leave rejected.');
    }
}

