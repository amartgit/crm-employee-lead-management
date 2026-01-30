<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'category',
        'contact_info',
        'city',
        'upload_date',
        'on_call',
        'status',
        'actions',
        'priority',
        'employee_id',
        'lead_source',
        'act_by'
    ];

    // Define relationship with Employee (this assumes each lead is associated with one employee)
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
// Accessor to decode 'act_by' JSON field into a more usable format
    public function getActivitiesAttribute()
    {
        return $this->act_by ? json_decode($this->act_by) : [];
    }

}
