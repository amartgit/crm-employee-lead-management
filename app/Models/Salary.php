<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'base_salary',
        'late_deductions',
        'absence_deductions',
        'total_salary',
    ];

    protected $casts = [
        'month' => 'date',
        'base_salary' => 'float',
        'late_deductions' => 'float',
        'absence_deductions' => 'float',
        'total_salary' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
