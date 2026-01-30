<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Breakemp extends Model
{
    protected $fillable = ['type', 'start_time', 'end_time', 'attendance_id'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    // Define the relationship with Attendance
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }


}
