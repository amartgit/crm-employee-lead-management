<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justdialleads extends Model
{
    protected $fillable = [
        'leadid', 'leadtype', 'prefix', 'name', 'mobile', 'phone', 'email', 'date',
        'category', 'city', 'area', 'brancharea', 'dncmobile', 'dncphone', 'company',
        'pincode', 'time', 'branchpin', 'parentid', 'lead_source'
    ];
}
