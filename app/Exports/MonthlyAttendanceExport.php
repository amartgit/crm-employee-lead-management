<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthlyAttendanceExport implements FromView
{
    protected $attendanceSummary;
    protected $month;

    public function __construct($attendanceSummary, $month)
    {
        $this->attendanceSummary = $attendanceSummary;
        $this->month = $month;
    }

    public function view(): View
    {
        return view('admin.attendance.export_monthly', [
            'attendanceSummary' => $this->attendanceSummary,
            'month' => $this->month
        ]);
    }
}
