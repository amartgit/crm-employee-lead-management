<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Breakemp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AttendanceController extends Controller
{
    public function dashboard()
    {
        $employee = Employee::where('employee_id', Auth::user()->employee_id)->first();
        $attendances = Attendance::where('employee_id', Auth::user()->employee_id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendance.dashboard', compact('employee', 'attendances'));
    }
    




    public function checkIn(Request $request)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        // Validate work_type input
        $request->validate([
            'work_type' => 'required|in:WFH,Office,OnSite',
        ]);

        // Find or create the attendance record for today
        $attendance = Attendance::where('employee_id', Auth::user()->employee_id)
            ->whereDate('date', $today)
            ->firstOrNew(['employee_id' => Auth::user()->employee_id, 'date' => $today]);

        // Check if the check-in time is already set
        if ($attendance->check_in) {
            return redirect()->route('attendance.dashboard')->with('error', 'You have already checked in today.');
        }

        // Only update the check-in time if it's different
        if ($attendance->check_in !== $now) {
            $attendance->check_in = $now;
            $attendance->work_type = $request->work_type;
            $attendance->Status = "Present";

            $attendance->save(); // Save only if there's a change
            return redirect()->route('attendance.dashboard')->with('success', 'Check-in successful.');
        }

        return redirect()->route('attendance.dashboard')->with('error', 'Check-in time is the same as the last check-in.');
    }

    // public function checkOut(Request $request)
    // {
    //     $today = Carbon::today();
    //     $now = Carbon::now();

    //     // Find today's attendance record for the current employee
    //     $attendance = Attendance::where('employee_id', Auth::user()->employee_id)
    //         ->whereDate('date', $today)
    //         ->first();

    //     // Check if the attendance record exists and if the employee has checked in
    //     if (!$attendance || !$attendance->check_in) {
    //         return redirect()->route('attendance.dashboard')->with('error', 'You need to check in first.');
    //     }

    //     // If already checked out, return an error message
    //     if ($attendance->check_out) {
    //         return redirect()->route('attendance.dashboard')->with('error', 'You have already checked out today.');
    //     }

    //     // Record the check-out time
    //     $attendance->check_out = $now;
    //     $attendance->save(); // Save the check-out time

    //     // Return success message
    //     return redirect()->route('attendance.dashboard')->with('success', 'Check-out successful.');
    // }

public function checkOut(Request $request)
{
    $today = Carbon::today();
    $now = Carbon::now();

    // Find today's attendance record for the current employee
    $attendance = Attendance::where('employee_id', Auth::user()->employee_id)
                            ->whereDate('date', $today)
                            ->first();

    // Check if the attendance record exists and if the employee has checked in
    if (!$attendance || !$attendance->check_in) {
        return redirect()->route('attendance.dashboard')->with('error', 'You need to check in first.');
    }

    // If already checked out, return an error message
    if ($attendance->check_out) {
        return redirect()->route('attendance.dashboard')->with('error', 'You have already checked out today.');
    }

    // Record the check-out time
    $attendance->check_out = $now;

    // Calculate total working hours (difference between check_in and check_out)
    $checkInTime = Carbon::parse($attendance->check_in);
    $checkOutTime = Carbon::parse($attendance->check_out);

    // Calculate the total working time in hours and minutes
    $totalWorkingHours = $checkInTime->diffInHours($checkOutTime);
    $totalWorkingMinutes = $checkInTime->diffInMinutes($checkOutTime) % 60;  // Get remaining minutes after hours

    // Calculate total working time in hours as a float (e.g., 8.5 hours)
    $totalWorkingTime = $totalWorkingHours + ($totalWorkingMinutes / 60);

    // Save the check-out time and total working time to the database
    $attendance->total_working_time = $totalWorkingTime;
    $attendance->save(); // Save the check-out time and total working time

    // Return the success message along with the formatted working time
    return redirect()->route('attendance.dashboard')->with('success', 'Check-out successful. Total working time: ' . $totalWorkingHours . ' hours ' . $totalWorkingMinutes . ' minutes.');
}




    public function addNote(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:255',
        ]);

        $attendance = Attendance::findOrFail($id);

        if ($attendance->employee_id !== Auth::user()->employee_id) {
            return redirect()->back()->with('error', 'You are not authorized to add notes to this attendance record.');
        }

        $attendance->notes = $request->notes;
        $attendance->save();

        return redirect()->back()->with('success', 'Note added successfully.');
    }

    public function logBreak(Request $request, $attendanceId)
    {
        // Get current time dynamically
        $currentTime = Carbon::now()->format('H:i');

        // Validate the form input
        $request->validate([
            'break_type' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Find the attendance record
        $attendance = Attendance::findOrFail($attendanceId);

        // Ensure the break start time is after the check-in time
        $checkInTime = Carbon::parse($attendance->check_in);
        $breakStartTime = Carbon::parse($request->input('start_time'));
        $breakEndTime = Carbon::parse($request->input('end_time'));

        // Validate times
        if ($breakStartTime <= $checkInTime) {
            return redirect()->back()->with('error', 'Break start time must be after your check-in time.');
        }

        if ($breakEndTime <= $breakStartTime) {
            return redirect()->back()->with('error', 'Break end time must be after the break start time.');
        }

        $checkOutTime = Carbon::parse($attendance->check_out);

        // if ($breakStartTime >= $checkOutTime || $breakEndTime >= $checkOutTime) {
        //     return redirect()->back()->with('error', 'Break time must be within your working hours (check-in to check-out).');
        // }

        // Check if break already exists for the same time
        $existingBreak = $attendance->breakemps->where('start_time', $request->input('start_time'))
            ->where('end_time', $request->input('end_time'))
            ->first();

        if ($existingBreak) {
            return redirect()->back()->with('error', 'This break has already been logged.');
        }

        // Create a new break entry linked to the attendance
        $break = new Breakemp();
        $break->type = $request->input('break_type');
        $break->start_time = $breakStartTime;
        $break->end_time = $breakEndTime;
        $break->attendance_id = $attendance->id;
        $break->save();

        return redirect()->back()->with('success', 'Break logged successfully');
    }
}
