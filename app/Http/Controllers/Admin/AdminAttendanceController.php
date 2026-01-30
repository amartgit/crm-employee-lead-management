<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Exports\MonthlyAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminAttendanceController extends Controller
{
    
    
     public function index(Request $request)
{
    $perPage = $request->get('per_page', 10); // Default to 10 if not provided
    $dateFilter = $request->input('date_filter');
    $employeeId = $request->input('employee_id'); // Employee ID filter
    $month = $request->input('month'); // Month filter

    // Sorting fields and direction
    $sortField = $request->input('sort_field', 'date'); // Default to 'date' for sorting
    $sortDirection = $request->input('sort_direction', 'asc'); // Default to 'asc'

    // Ensure the direction is either 'asc' or 'desc'
    if (!in_array($sortDirection, ['asc', 'desc'])) {
        $sortDirection = 'asc';
    }

    // Apply filters and sorting
    $attendances = Attendance::when($employeeId, function ($query) use ($employeeId) {
        return $query->where('employee_id', 'like', "%{$employeeId}%"); // Filter by employee ID
    })
    ->when($dateFilter, function ($query) use ($dateFilter) {
        switch ($dateFilter) {
            case 'yesterday':
                return $query->whereDate('date', Carbon::yesterday()->format('Y-m-d'));
            case 'today':
                return $query->whereDate('date', Carbon::today()->format('Y-m-d'));
            case 'last_week':
                return $query->whereBetween('date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            default:
                return $query;
        }
    })
    ->when($month, function ($query) use ($month) {
        return $query->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
                     ->whereYear('date', Carbon::createFromFormat('Y-m', $month)->year); // Filter by selected month
    })
    ->orderBy($sortField, $sortDirection) // Apply sorting
    ->paginate($perPage);

    return view('admin.attendance.index', compact('attendances'));
}


    public function toggleVerification($id)
    {
        try {
            // Find the attendance record by ID
            $attendance = Attendance::findOrFail($id);
            
            // Toggle the verification status
            $attendance->verified = !$attendance->verified;
            $attendance->save();

            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'Attendance verification updated successfully!',
            ]);
        } catch (\Exception $e) {
            // If any exception occurs, return an error message
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request: ' . $e->getMessage(),
            ]);
        }
    }



public function update(Request $request, $id)
{
    // Find attendance entry
    $attendance = Attendance::findOrFail($id);
    $attendanceDate = $attendance->date->format('Y-m-d'); // Get the date

    // Validate input
    $request->validate([
        'check_in' => ['required', 'date'],
        'check_out' => ['required', 'date', 'after:check_in'],
    ], [
        'check_in.required' => 'Check-in time is required.',
        'check_out.required' => 'Check-out time is required.',
        'check_out.after' => 'Check-out time must be after Check-in time.',
    ]);

    // Convert to Carbon instances
    $checkIn = Carbon::parse($request->check_in);
    $checkOut = Carbon::parse($request->check_out);

    // Ensure check-in and check-out are on the same day
    if ($checkIn->toDateString() !== $checkOut->toDateString()) {
        return response()->json([
            'success' => false,
            'message' => 'Check-in and Check-out must be on the same day!'
        ], 422);
    }

    // Ensure check-in and check-out match attendance date
    if ($checkIn->toDateString() !== $attendanceDate) {
        return response()->json([
            'success' => false,
            'message' => 'Check-in and Check-out must match the attendance date (' . $attendanceDate . ')!'
        ], 422);
    }

    // Update attendance
    $attendance->update([
        'check_in' => $checkIn,
        'check_out' => $checkOut,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Attendance updated successfully!'
    ]);
}



public function updateAttendanceStatus(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    $request->validate([
        'status' => 'required|in:Present,Halfday,Absent,Leave,Holiday',
    ]);

    $attendance->update([
        'status' => $request->status,
    ]);

    return response()->json(['success' => true, 'message' => 'Attendance status updated successfully!']);
}



// for another pages/

    // Display Monthly Attendance Management Page
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $employees = Employee::all();

        return view('admin.attendance.monthly', compact('employees', 'month'));
    }


    // Store Attendance for a Day
    public function storeForDay($employee_id, $date, Request $request)
    {
        $date = Carbon::parse($date); // Parse the date
        $employee = Employee::where('employee_id', $employee_id)->first(); // Find employee by ID

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found!');
        }


        // Check if attendance already exists for this employee on this day
        $existingAttendance = Attendance::where('employee_id', $employee->employee_id)
            ->where('date', $date)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance for this date already exists!');
        }

        // Store the new attendance record
        $attendance = new Attendance();
        $attendance->employee_id = $employee->employee_id;
        $attendance->date = $date;
        $attendance->status = $request->status;
        $attendance->save();

        return redirect()->back()->with('success', 'Attendance stored successfully!');
    }



    public function updateStatus(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id); // Find attendance record by ID

        // Update status
        $attendance->status = $request->status;
        $attendance->save();

        return redirect()->route('admin.attendance.monthly', ['month' => Carbon::parse($attendance->date)->format('Y-m')])
            ->with('success', 'Attendance status updated successfully!');
    }
    
    // monthlyAttendancereport
public function monthlyAttendancereport(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));

    $attendances = Attendance::whereMonth('date', '=', Carbon::parse($month)->month)
        ->whereYear('date', '=', Carbon::parse($month)->year)
        ->get();

    $attendanceSummary = [];
    $employees = Employee::all();

    foreach ($employees as $employee) {
        $records = $attendances->where('employee_id', $employee->employee_id);

        $presentDays = 0;
        $halfDays = 0;

        $leaveDays = 0;
        $holidayDays = 0;
        $absentDays = 0;
        $totalWorkingMinutes = 0;  // Store total working time in minutes

        foreach ($records as $attendance) {
            switch ($attendance->status) {
                case 'Present':
                    $presentDays++;
                    // Ensure the total working minutes is a number
                    $workingMinutes = (int) $attendance->calculateTotalWorkingTime();
                    $totalWorkingMinutes += $workingMinutes;  // Add to total working minutes
                    break;

                case 'Leave':
                    $leaveDays++;
                    break;
                    
                case 'Halfday':
                    $halfDays++;
                    break;

                case 'Holiday':
                    $holidayDays++;
                    break;

                case 'Absent':
                    $absentDays++;
                    break;
            }
        }

        // Add the employee's summary to the overall attendance summary
        $attendanceSummary[] = [
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->fname . ' ' . $employee->lname,
            'present_days' => $presentDays,
            'Half_days' => $halfDays,
            'leave_days' => $leaveDays,
            'holiday_days' => $holidayDays,
            'absent_days' => $absentDays,
            'total_working_minutes' => $totalWorkingMinutes,  // Store total minutes
        ];
    }

    return view('admin.attendance.monthSummary', compact('attendanceSummary', 'month'));
}

public function exportMonthlyAttendance(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));

    $attendances = Attendance::whereMonth('date', '=', Carbon::parse($month)->month)
        ->whereYear('date', '=', Carbon::parse($month)->year)
        ->get();

    $attendanceSummary = [];
    $employees = Employee::all();

    foreach ($employees as $employee) {
        $records = $attendances->where('employee_id', $employee->employee_id);

        $presentDays = 0;
        $halfDays = 0;
        $leaveDays = 0;
        $holidayDays = 0;
        $absentDays = 0;
        $totalWorkingMinutes = 0;

        foreach ($records as $attendance) {
            switch ($attendance->status) {
                case 'Present':
                    $presentDays++;
                    $workingMinutes = (int) $attendance->calculateTotalWorkingTime();
                    $totalWorkingMinutes += $workingMinutes;
                    break;
                case 'Leave':
                    $leaveDays++;
                    break;
                case 'Halfday':
                    $halfDays++;
                    break;
                case 'Holiday':
                    $holidayDays++;
                    break;
                case 'Absent':
                    $absentDays++;
                    break;
            }
        }

        $attendanceSummary[] = [
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->fname . ' ' . $employee->lname,
            'present_days' => $presentDays,
            'Half_days' => $halfDays,
            'leave_days' => $leaveDays,
            'holiday_days' => $holidayDays,
            'absent_days' => $absentDays,
            'total_working_minutes' => $totalWorkingMinutes,
        ];
    }

    return Excel::download(new MonthlyAttendanceExport($attendanceSummary, $month), 'Monthly_Attendance_Report.xlsx');
}


// Display attendance management page with dynamic search
    // public function index(Request $request)
    // {
    //     $query = Attendance::query();

    //     // Dynamic search filters
    //     if ($request->has('employee_id') && $request->employee_id != '') {
    //         $query->where('employee_id', 'like', '%' . $request->employee_id . '%');
    //     }

    //     if ($request->has('date_from') && $request->has('date_to') && $request->date_from != '' && $request->date_to != '') {
    //         $query->whereBetween('date', [$request->date_from, $request->date_to]);
    //     }

    //     // if ($request->has('verified') && $request->verified != '') {
    //     //     $query->where('verified', $request->verified);
    //     // }

    //     // if ($request->has('status') && $request->status != '') {
    //     //     $query->where('status', 'like', '%' . $request->status . '%');
    //     // }

    //     // Paginate results
    //     $attendances = $query->paginate(10);

    //     return view('admin.attendance.index', compact('attendances'));
    // }


    // // Toggle attendance verification
    // public function toggleVerification($id)
    // {
    //     $attendance = Attendance::findOrFail($id);
    //     $attendance->verified = !$attendance->verified;
    //     $attendance->save();

    //     return redirect()->route('admin.attendance.index')->with('success', 'Attendance verification updated!');
    // }

  // public function update(Request $request, $id)
    // {
    //     // Find the attendance record by ID
    //     $attendance = Attendance::findOrFail($id);

    //     // Validate the input
    //     $request->validate([
    //         'check_in' => 'required|date',
    //         'check_out' => 'required|date',
    //         'notes' => 'nullable|string',
    //     ]);

    //     // Update the attendance record
    //     $attendance->update([
    //         'check_in' => $request->input('check_in'),
    //         'check_out' => $request->input('check_out'),
    //         'notes' => $request->input('notes'),
    //     ]);

    //     // Redirect back with a success message
    //     return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully!');
    // }
    
//     public function update(Request $request, $id)
// {
//     $attendance = Attendance::findOrFail($id);

//     // Validate input
//     $request->validate([
//         'check_in' => 'required|date',
//         'check_out' => 'required|date|after:check_in',
//     ]);

//     // Convert times to Carbon instances
//     $checkIn = Carbon::parse($request->check_in);
//     $checkOut = Carbon::parse($request->check_out);

//     // Ensure check-in and check-out are on the same day
//     if ($checkIn->toDateString() !== $checkOut->toDateString()) {
//         return response()->json(['success' => false, 'message' => 'Check-in and Check-out must be on the same day!'], 422);
//     }

//     // Update attendance record
//     $attendance->update([
//         'check_in' => $checkIn,
//         'check_out' => $checkOut,
//     ]);

//     return response()->json(['success' => true, 'message' => 'Attendance updated successfully!']);
// }
  
}
