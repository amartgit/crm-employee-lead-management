<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\LeadUpdated;
use App\Models\Lead;
use App\Notifications\LeadUpdatedNotification;

use App\Models\User;
use App\Models\Employee;



class LeadController extends Controller
{

public function index(Request $request)
{
    $query = Lead::where(function ($query) {
        $query->whereNull('employee_id')
            ->orWhere('employee_id', Auth::id());
    });

    // Add the search filter if search term exists
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('company', 'like', "%$search%")
                ->orWhere('contact_info', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('lead_source', 'like', "%$search%")
                ->orWhere('id', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");

        });
    }

    // Apply sorting based on the request
    $sortField = $request->get('sort_field', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');
    $leads = $query->orderBy($sortField, $sortDirection)->paginate(20);

    return view('employee.leads.index', compact('leads'));
}

public function fetch(Request $request)
{
    $query = Lead::where(function ($query) {
        $query->whereNull('employee_id')
            ->orWhere('employee_id', Auth::id());
    });

    // Add the search filter if search term exists
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('company', 'like', "%$search%")
                ->orWhere('contact_info', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('lead_source', 'like', "%$search%")
                ->orWhere('id', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");

        });
    }

    // Apply sorting based on the request
    $sortField = $request->get('sort_field', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');
    $leads = $query->orderBy($sortField, $sortDirection)->paginate(20);

    return view('employee.leads.partials.lead-row', compact('leads'));
}


    // public function updateStatus(Request $request, $id)
    // {
        
    //       // Validate Input
    // $request->validate([
    //     'status' => 'required|string',
    // ]);

    //     $lead = Lead::findOrFail($id);

    //     if (in_array($request->status, ['Almost Closed', 'Closed'])) {
    //         $lead->employee_id = Auth::id();
    //     } else {
    //         $lead->employee_id = null;
    //     }

    //     $employee = Auth::user()->employee;

    //     $employee_id = $employee ? $employee->employee_id : null;
    //     $employee_name = $employee ? $employee->fname : null;  // Capture the employee's name

    //     $actionLog = json_decode($lead->act_by, true) ?? [];
    //     $actionLog[] = [
    //         'status' => $request->status,
    //         'act_by' => Auth::id(),
    //         'name' => $employee_name,  // Log the employee's name
    //         'employee_id' => $employee_id, // Store the employee_id (VI001, VI002, etc.)
    //         'timestamp' => now(),
    //     ];

    //     $lead->status = $request->status;
    //     $lead->act_by = json_encode($actionLog);
    //     $lead->save();

    //     return redirect()->route('employee.leads.index')->with('success', 'Lead status updated successfully');
    // }
    
    public function updateStatus(Request $request, $id)
{
    
    try {
    // Validate Input
    $request->validate([
        'status' => 'nullable|string',
    ]);

    $lead = Lead::findOrFail($id);

    if (in_array($request->status, ['Almost Closed', 'Closed'])) {
        $lead->employee_id = Auth::id();
    } else {
        $lead->employee_id = null;
    }

    $employee = Auth::user()->employee;

    $employee_id = $employee ? $employee->employee_id : null;
    $employee_name = $employee ? $employee->fname : null;  // Capture the employee's name

    $actionLog = json_decode($lead->act_by, true) ?? [];
    $actionLog[] = [
        'status' => $request->status,
        'act_by' => Auth::id(),
        'name' => $employee_name,  // Log the employee's name
        'employee_id' => $employee_id, // Store the employee_id (VI001, VI002, etc.)
        'timestamp' => now(),
    ];

    $lead->status = $request->status;
    $lead->act_by = json_encode($actionLog);
    $lead->save();

    // return redirect()->route('employee.leads.index')->with('status', [
    //     'id' => $id,
    //     'message' => 'Lead status updated successfully',
    //     'type' => 'success'
    // ]);
    
       return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully',
            'status' => $request->status,
            'id' => $id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing the request: ' . $e->getMessage(),
        ]);
    }
}


    // public function toggleOnCall($id)
    // {
    //     $employee = Auth::user()->employee;
    //     $lead = Lead::findOrFail($id);

    //     $existingCall = Lead::where('employee_id', Auth::id())
    //                         ->where('on_call', true)
    //                         ->first();

    //     if ($existingCall && $existingCall->id !== $lead->id) {
    //         return redirect()->route('employee.leads.index')->with('error', 'You are already on a call with another lead. Please end the current call before toggling another one.');
    //     }

    //     if ($lead->employee_id === Auth::id() || !$lead->employee_id) {
    //         $lead->on_call = !$lead->on_call;
    //         $lead->save();

    //         $employee_id = $employee ? $employee->employee_id : null;
    //         $employee_name = $employee ? $employee->fname : null;
    //         $actionLog = json_decode($lead->act_by, true) ?? [];
    //         $actionLog[] = [
    //             'on_call' => $lead->on_call,
    //             'act_by' => Auth::id(),
    //             'name' => $employee_name,  // Log the employee's name
    //             'employee_id' => $employee_id, // Store the employee_id (VI001, VI002, etc.)
    //             'timestamp' => now(),
    //         ];

    //         $lead->act_by = json_encode($actionLog);
    //         $lead->save();

    //         if ($lead->on_call) {
    //             if ($existingCall && $existingCall->id !== $lead->id) {
    //                 $existingCall->on_call = false;
    //                 $existingCall->save();
    //             }
    //         }

    //         return redirect()->route('employee.leads.index');
    //     }

    //     return redirect()->route('employee.leads.index')->with('error', 'You cannot update the call status because the lead is assigned to another user.');
    // }

public function toggleOnCall($id)
{
    try {
        $employee = Auth::user()->employee;
        $lead = Lead::findOrFail($id);

        // Check if the user is already on a call with another lead
        $existingCall = Lead::where('employee_id', Auth::id())
                            ->where('on_call', true)
                            ->first();

if ($existingCall && $existingCall->id !== $lead->id) {
    return response()->json([
        'success' => false,
        'message' => 'Please end the current call on Lead ' . $existingCall->id . ' before starting another one.'
    ]);
}


        // Check if the lead is owned by this employee or unassigned
        if ($lead->employee_id === Auth::id() || !$lead->employee_id) {

            // Toggle call status
            $lead->on_call = !$lead->on_call;

            // Assign employee_id if the lead was unassigned and call is starting
            if ($lead->on_call && !$lead->employee_id) {
                $lead->employee_id = Auth::id();
            }

            $lead->save();

            // Log the action
            $employee_id = $employee ? $employee->employee_id : null;
            $employee_name = $employee ? $employee->fname : null;
            $actionLog = json_decode($lead->act_by, true) ?? [];
            $actionLog[] = [
                'on_call' => $lead->on_call,
                'act_by' => Auth::id(),
                'name' => $employee_name,
                'employee_id' => $employee_id,
                'timestamp' => now(),
            ];

            $lead->act_by = json_encode($actionLog);
            $lead->save();

            // If the call has started, ensure any previous call is ended
            if ($lead->on_call && $existingCall && $existingCall->id !== $lead->id) {
                $existingCall->on_call = false;
                $existingCall->save();
            }

            return response()->json([
                'success' => true,
                'message' => $lead->on_call ? 'Call started successfully.' : 'Call ended successfully.',
                'on_call' => $lead->on_call
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'You cannot update the call status because the lead is assigned to another user.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing the request: ' . $e->getMessage(),
        ]);
    }
}



    // public function toggleOnCall($id)
    // {
    //     $employee = Auth::user()->employee; // This fetches the employee associated with the logged-in user

    //     $lead = Lead::findOrFail($id);

    //     if ($lead->employee_id === Auth::id() || !$lead->employee_id) {

    //         $lead->on_call = !$lead->on_call;
    //         $lead->save();
    //     }

    //     $actionLog = json_decode($lead->act_by, true) ?? [];
    //     $actionLog[] = [
    //         'on_call' => $lead->on_call,
    //         'act_by' => Auth::id(),
    //         'timestamp' => now(),
    //     ];

    //     $lead->act_by = json_encode($actionLog);
    //     $lead->save();

    //     return redirect()->route('employee.leads.index')->with('success', 'Lead call status updated successfully');
    // }

    // public function editActions(Request $request, $id)
    // {
    //     $request->validate([
    //         'actions' => 'required|string',
    //     ]);

    //     $lead = Lead::findOrFail($id);

    //     if ($lead->employee_id === Auth::id() || !$lead->employee_id) {

    //         $lead->actions = $request->actions;
    //         $lead->save();
    //     }
        
    //     $employee = Auth::user()->employee;

    //     $employee_id = $employee ? $employee->employee_id : null;
    //     $employee_name = $employee ? $employee->fname : null;

    //     $actionLog = json_decode($lead->act_by, true) ?? [];
    //     $actionLog[] = [
    //         'actions' => $request->actions,
    //         'act_by' => Auth::id(),
    //         'name' => $employee_name,  // Log the employee's name
    //         'employee_id' => $employee_id, // Store the employee_id (VI001, VI002, etc.)
    //         'timestamp' => now(),
    //     ];

    //     $lead->act_by = json_encode($actionLog);
    //     $lead->save();

    //     return redirect()->route('employee.leads.index')->with('success', 'Lead actions updated successfully');
    // }
    
    public function editActions(Request $request, $id)
{
    try{
    $request->validate([
        'actions' => 'required|string',
    ]);

    $lead = Lead::findOrFail($id);

    if ($lead->employee_id === Auth::id() || !$lead->employee_id) {
        $lead->actions = $request->actions;
        $lead->save();
    }

    $employee = Auth::user()->employee;
    $employee_id = $employee ? $employee->employee_id : null;
    $employee_name = $employee ? $employee->fname : null;

    $actionLog = json_decode($lead->act_by, true) ?? [];
    $actionLog[] = [
        'actions' => $request->actions,
        'act_by' => Auth::id(),
        'name' => $employee_name,  // Log the employee's name
        'employee_id' => $employee_id, // Store the employee_id (VI001, VI002, etc.)
        'timestamp' => now(),
    ];

    $lead->act_by = json_encode($actionLog);
    $lead->save();

    // Return a JSON response indicating success
    return response()->json([
        'success' => true,
        'message' => 'Lead actions updated successfully!',
    ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing the request: ' . $e->getMessage(),
        ]);
    }
}


    public function myleads()
    {
        $leads = Lead::where(function ($query) {
            $query->Where('employee_id', Auth::id());
        })->orderBy('priority', 'asc')->paginate(10);

        return view('employee.leads.myleads', compact('leads'));
    }
    
public function myLeadsActivity(Request $request)
{
    // Get the current authenticated user
    $userId = Auth::id();

    // Start building the query for leads
    $query = Lead::whereJsonContains('act_by', ['act_by' => $userId]);

    // Apply filter by status if provided
    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    // Apply sorting if sort_field and sort_direction are set in the request
    if ($request->has('sort_field') && in_array($request->sort_field, ['updated_at', 'created_at'])) {
        $sortField = $request->sort_field;
        $sortDirection = $request->sort_direction == 'asc' ? 'asc' : 'desc'; // Default to 'desc' if not valid
        $query->orderBy($sortField, $sortDirection);
    }else {
        // Default sort by ID DESC
        $query->orderBy('id', 'desc');
    }
    // Paginate the results (10 leads per page)
    $leads = $query->paginate(10);

    // Get all available lead sources for the filter dropdown
    $lead_sources = Lead::distinct()->pluck('lead_source')->toArray();

    // Return the view with pagination data and the filters
    return view('employee.leads.my_activity', compact('leads', 'lead_sources'));
}





}
