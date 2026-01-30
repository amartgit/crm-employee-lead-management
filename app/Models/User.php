<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable

{
    use HasFactory, Notifiable, SoftDeletes ;

    protected $fillable = [
        'name','employee_id', 'password', 'phone_number', 'role'
    ];
    
    protected $with = ['employee']; // Always load employee details


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function employee()
{
    return $this->hasOne(Employee::class, 'employee_id', 'employee_id');
}

 // Define the inverse relationship (not really needed in this case, but it's good practice)
    public function logins()
    {
        return $this->hasMany(Login::class, 'employee_id');
    }
    
    public function deviceTokens()
{
    return $this->hasMany(\App\Models\DeviceToken::class);
}

}
