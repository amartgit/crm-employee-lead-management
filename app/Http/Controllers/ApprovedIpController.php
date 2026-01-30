<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllowedIp;

class ApprovedIpController extends Controller
{
    public function index()
    {
        $ips = AllowedIp::orderBy('created_at', 'desc')->paginate(20); // 10 IPs per page
        return view('admin.approved_ips', compact('ips'));
    }

    // public function store(Request $request)
    // {
    //     try {
    //     $request->validate([
    //         'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
    //         'label' => 'nullable|string',
    //     ]);

    //     AllowedIp::create([
    //         'ip_address' => $request->ip_address,
    //         'label' => $request->label,
    //         'approved' => false,
    //     ]);

    //     return response()->json(['status' => 'success']);
    //     } catch (\Exception $e) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Failed to add IPs.',
    //         'error' => $e->getMessage()
    //     ], 500);
    // }
    // }
    
public function store(Request $request)
{
    try {
        $request->validate([
            'ip_address' => 'required|string',
            'label' => 'nullable|string',
        ]);

        // Split and clean input IPs
        $rawIps = preg_split('/[\s,]+/', $request->ip_address, -1, PREG_SPLIT_NO_EMPTY);

        // Remove duplicates in input
        $uniqueIps = array_unique($rawIps);

        $added = [];
        foreach ($uniqueIps as $ip) {
            if (
                filter_var($ip, FILTER_VALIDATE_IP) &&
                !AllowedIp::where('ip_address', $ip)->exists()
            ) {
                AllowedIp::create([
                    'ip_address' => $ip,
                    'label' => $request->label,
                    'approved' => false,
                ]);
                $added[] = $ip;
            }
        }

        if (count($added) === 0) {
            return response()->json([
                'status' => 'warning',
                'message' => 'No new valid IPs were added (may be duplicates or invalid).'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => count($added) . ' IP(s) added successfully.',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to add IP(s).',
            'error' => $e->getMessage()
        ], 500);
    }
}

        public function bulkUpdate(Request $request)
{
    try {
        $allIps = AllowedIp::all();

        // Get submitted IPs from form (checkboxes checked)
        $submittedIps = $request->input('ips', []);

        if (!is_array($submittedIps)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid IP data format.',
            ], 400);
        }

        foreach ($allIps as $ip) {
            $isApproved = array_key_exists($ip->id, $submittedIps);
            $ip->approved = $isApproved;
            $ip->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'IP approval statuses updated successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update IPs.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
