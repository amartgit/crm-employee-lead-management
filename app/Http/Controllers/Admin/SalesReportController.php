<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Employee;
    use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $totalLeads = DB::table('leads')->count();

        $statusCounts = DB::table('leads')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $leads = DB::table('leads')->select('id', 'act_by')->get();

        $dailyCalls = [];
        $monthlyCalls = [];
        $dailyStatusCounts = [];
        $monthlyStatusCounts = [];

        foreach ($leads as $lead) {
            $actions = json_decode($lead->act_by, true);
            if (!is_array($actions)) continue;

            foreach ($actions as $action) {
                if (!isset($action['timestamp'])) continue;

                $timestamp = Carbon::parse($action['timestamp']);
                $employee = $action['employee_id'] ?? 'Unknown';
                $status = $action['status'] ?? null;

                if (!empty($action['on_call'])) {
                    if ($timestamp->toDateString() == $selectedDate) {
                        $dailyCalls[$employee] = ($dailyCalls[$employee] ?? 0) + 1;
                    }
                    if ($timestamp->format('Y-m') == $selectedMonth) {
                        $monthlyCalls[$employee] = ($monthlyCalls[$employee] ?? 0) + 1;
                    }
                }

                if ($status !== null) {
                    $status = $status ?: 'No Status';

                    if ($timestamp->toDateString() == $selectedDate) {
                        $dailyStatusCounts[$employee][$status] = ($dailyStatusCounts[$employee][$status] ?? 0) + 1;
                    }
                    if ($timestamp->format('Y-m') == $selectedMonth) {
                        $monthlyStatusCounts[$employee][$status] = ($monthlyStatusCounts[$employee][$status] ?? 0) + 1;
                    }
                }
            }
        }

$employeeIds = collect(array_merge(
    array_keys($dailyCalls),
    array_keys($monthlyCalls),
    array_keys($dailyStatusCounts),
    array_keys($monthlyStatusCounts)
))->unique();

// Assuming your Employee model uses 'employee_id' (not 'id')
$employees = Employee::whereIn('employee_id', $employeeIds)->get()->keyBy('employee_id');

$getEmployeeName = function ($id) use ($employees) {
    return isset($employees[$id]) ? $employees[$id]->fname . ' ' . $employees[$id]->lname : 'Unknown';
};


        return view('admin.salesreports.index', compact(
            'totalLeads',
            'statusCounts',
            'dailyCalls',
            'monthlyCalls',
            'dailyStatusCounts',
            'monthlyStatusCounts',
            'selectedDate',
            'selectedMonth',
            'getEmployeeName'
        ));
    }
    
    


 public function exportExcel(Request $request)
    {
        $totalLeads = DB::table('leads')->count();

        $statusCounts = DB::table('leads')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $leads = DB::table('leads')->select('id', 'act_by')->get();

        $dailyCalls = [];
        $monthlyCalls = [];
        $dailyStatusCounts = [];
        $monthlyStatusCounts = [];

        foreach ($leads as $lead) {
            $actions = json_decode($lead->act_by, true);
            if (!is_array($actions)) continue;

            foreach ($actions as $action) {
                if (!isset($action['timestamp'])) continue;

                $timestamp = Carbon::parse($action['timestamp']);
                $employee = $action['employee_id'] ?? 'Unknown';
                $status = $action['status'] ?? null;

                if (!empty($action['on_call'])) {
                    if ($timestamp->toDateString() == $selectedDate) {
                        $dailyCalls[$employee] = ($dailyCalls[$employee] ?? 0) + 1;
                    }
                    if ($timestamp->format('Y-m') == $selectedMonth) {
                        $monthlyCalls[$employee] = ($monthlyCalls[$employee] ?? 0) + 1;
                    }
                }

                if ($status !== null) {
                    $status = $status ?: 'No Status';

                    if ($timestamp->toDateString() == $selectedDate) {
                        $dailyStatusCounts[$employee][$status] = ($dailyStatusCounts[$employee][$status] ?? 0) + 1;
                    }
                    if ($timestamp->format('Y-m') == $selectedMonth) {
                        $monthlyStatusCounts[$employee][$status] = ($monthlyStatusCounts[$employee][$status] ?? 0) + 1;
                    }
                }
            }
        }

        $employeeIds = collect(array_merge(
            array_keys($dailyCalls),
            array_keys($monthlyCalls),
            array_keys($dailyStatusCounts),
            array_keys($monthlyStatusCounts)
        ))->unique();

        $employees = Employee::whereIn('employee_id', $employeeIds)->get()->keyBy('employee_id');

        $getEmployeeName = function ($id) use ($employees) {
            return isset($employees[$id]) ? $employees[$id]->fname . ' ' . $employees[$id]->lname : 'Unknown';
        };

        $data = compact(
            'totalLeads',
            'statusCounts',
            'dailyCalls',
            'monthlyCalls',
            'dailyStatusCounts',
            'monthlyStatusCounts',
            'selectedDate',
            'selectedMonth',
            'getEmployeeName'
        );

        return Excel::download(new SalesReportExport($data), 'Sales_Report.xlsx');
    }
}
