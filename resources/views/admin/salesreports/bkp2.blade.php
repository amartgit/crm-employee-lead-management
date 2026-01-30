<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Employee; 

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

        foreach ($leads as $lead) {
            $actions = json_decode($lead->act_by, true);
            if (!is_array($actions)) continue;

            foreach ($actions as $action) {
                if (!isset($action['on_call']) || !$action['on_call']) continue;

                $employee = $action['employee_id'] ?? 'Unknown';
                $timestamp = Carbon::parse($action['timestamp']);

                // Daily count
                if ($timestamp->toDateString() == $selectedDate) {
                    $dailyCalls[$employee] = ($dailyCalls[$employee] ?? 0) + 1;
                }

                // Monthly count
                if ($timestamp->format('Y-m') == $selectedMonth) {
                    $monthlyCalls[$employee] = ($monthlyCalls[$employee] ?? 0) + 1;
                }
            }
        }
        
        $dailyStatusCounts = [];
$monthlyStatusCounts = [];

foreach ($leads as $lead) {
    $actions = json_decode($lead->act_by, true);
    if (!is_array($actions)) continue;

    foreach ($actions as $action) {
        if (!isset($action['employee_id']) || !isset($action['status'])) continue;

        $employee = $action['employee_id'];
        $status = $action['status'] ?: 'No Status';
        $timestamp = Carbon::parse($action['timestamp']);

        // Daily Status Count
        if ($timestamp->toDateString() == $selectedDate) {
            $dailyStatusCounts[$employee][$status] = ($dailyStatusCounts[$employee][$status] ?? 0) + 1;
        }

        // Monthly Status Count
        if ($timestamp->format('Y-m') == $selectedMonth) {
            $monthlyStatusCounts[$employee][$status] = ($monthlyStatusCounts[$employee][$status] ?? 0) + 1;
        }
    }
}


        return view('admin.salesreports.index', compact(
            'totalLeads',
            'statusCounts',
            'dailyCalls',
            'monthlyCalls',
             'dailyStatusCounts',
    'monthlyStatusCounts',
            'selectedDate',
            'selectedMonth'
        ));
    }
}
