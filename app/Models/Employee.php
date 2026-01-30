<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'fname',
        'mname',
        'lname',
        'phone_number',
        'whatsapp_number',
        'department',
        'dob',
        'gender',
        'mailid',
        'address',
        'Emergency_contact',
        'blood_group',
    ];

    // Employee model (app/Models/Employee.php)
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }
    
 public function permissions()
    {
        return $this->hasMany(EmployeePermission::class, 'employee_id');
    }
    
public function hasFeature($feature)
{
    return $this->permissions()->where('feature', $feature)->where('allowed', true)->exists();

}

}
