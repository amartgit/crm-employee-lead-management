<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'notes',
        'work_type',
        'verified',
        'status',
        'total_working_time',
        'total_break_time'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'verified' => 'boolean',
        'total_working_time' => 'integer',
        'total_break_time' => 'integer',

    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }


    public function breakemps()
    {
        return $this->hasMany(Breakemp::class);
    }

    // Automatically calculate working and break time after save

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }


public function calculateTotalWorkingTime()
{
    if ($this->check_in && $this->check_out) {
        $checkInTime = Carbon::parse($this->check_in);
        $checkOutTime = Carbon::parse($this->check_out);

        $workingTime = $checkInTime->diffInMinutes($checkOutTime);
        return $workingTime;  // Should return an integer (number of minutes)
    }

    return 0;  // Return 0 when no valid check-in or check-out
}


    /**
     * Calculate total break time in minutes.
     */
    public function calculateTotalBreakTime()
    {
        $totalBreakTime = 0;

        foreach ($this->breakemps as $break) {
            if ($break->start_time && $break->end_time) {
                $totalBreakTime += $break->start_time->diffInMinutes($break->end_time);
            }
        }

        return $totalBreakTime;
    }


}
