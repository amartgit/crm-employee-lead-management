<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Login extends Model
{
    protected $table = 'logins';

    protected $fillable = [
        'employee_id',
        'login_time',
        'logout_time',
        'ip_address',
        'session_id',

    ];

    public $timestamps = true;
    
  public function employee()
    {
        return $this->belongsTo(employee::class, 'employee_id', 'employee_id');
    }

}
