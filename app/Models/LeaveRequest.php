<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/LeaveRequest.php
class LeaveRequest extends Model
{
    protected $fillable = ['employee_id', 'start_date', 'end_date', 'leave_type', 'reason', 'status'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
