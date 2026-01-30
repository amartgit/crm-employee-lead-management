<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Justdialleads;
use App\Models\Lead;
use App\Models\Employee;
use App\Imports\LeadsImport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminLeadController extends Controller
{
    /**
     * Handle the incoming lead data (GET or POST).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
//      public function receivejustdialleads(Request $request)
// {
//     Log::info('Request Method:', ['method' => $request->method()]);
//     Log::info('Request Data:', $request->all());

//     if ($request->isMethod('post')) {
//         return response()->json([
//             'status' => 'success',
//             'message' => 'Post method received!',
//             'data' => $request->all()
//         ], 200);
//     }

//     return response()->json([
//         'status' => 'error',
//         'message' => 'Only POST method is allowed'
//     ], 405);
// }
     
    //      public function Import(Request $request)
    // {
    //     if ($request->isMethod('post')) {
    //         // Validate file upload
    //         $validator = Validator::make($request->all(), [
    //             'file' => 'required|mimes:xlsx,csv|max:2048',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()->first()], 400);
    //         }

    //         $file = $request->file('file');

    //         try {
    //             // Using the LeadsImport class to import data
    //             Excel::import(new LeadsImport, $file);

    //             return response()->json(['message' => 'Leads imported successfully'], 200);
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Error processing file: ' . $e->getMessage()], 500);
    //         }
    //     } else {
    //         // Show the import form for GET request
    //         return view('admin.leads.import');
    //     }
    // }
    
    public function Import(Request $request)
{
    if ($request->isMethod('post')) {
        // Validate file upload
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $file = $request->file('file');

        try {
            // Using the LeadsImport class to import data
            Excel::import(new LeadsImport, $file);

            // **Updated Code: Fetching the imported leads**
            $leads = Lead::latest()->take(10)->get();  // Get the latest 10 leads (you can adjust the query)

            // Return success message along with imported leads
            return response()->json([
                'message' => 'Leads imported successfully',
                'leads' => $leads // **Updated: Sending leads back as part of the response**
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing file: ' . $e->getMessage()], 500);
        }
    } else {
        // Show the import form for GET request
        return view('admin.leads.import');
    }
}


public function showJustdialLeads(Request $request)
{
    // Fetch all Justdialleads, optionally apply filtering if needed
    $search = $request->input('search');
    
    // Start the query for fetching Justdial leads
    $leads = Justdialleads::query();

    if ($search) {
        // Apply search filters
        $leads->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('company', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%");
        });
    }

    // Order the leads by created_at in descending order
    $leads = $leads->orderBy('created_at', 'desc')->paginate(20);

    // Return the view to display the leads
    return view('admin.leads.justdialleads', compact('leads', 'search'));
}


    public function receivejustdialleads(Request $request)
    {
        try {
        
        if ($request->isMethod('post')) {

        // Validate the incoming request data
        $data = $request->validate([
            'leadid' => 'required|string|unique:justdialleads,leadid',
            'leadtype' => 'required|string',
            'prefix' => 'nullable|string|max:10',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:255',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'brancharea' => 'nullable|string|max:255',
            'dncmobile' => 'required|integer',
            'dncphone' => 'required|integer',
            'company' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:50',
            'time' => 'required|date_format:H:i:s',
            'branchpin' => 'nullable|string|max:50',
            'parentid' => 'required|string|max:255',
            'lead_source' => 'nullable|string|max:255'
        ]);

        // Check if the leadid already exists in the database
        $existingLead = Justdialleads::where('leadid', $data['leadid'])->first();

        if ($existingLead) {
            // If the lead already exists, return a response indicating no new lead was added
            return response()->json(['message' => 'SUCCESS'], 200);
        }

        // Store the lead data into the database
        Justdialleads::create([
            'leadid' => $data['leadid'],
            'leadtype' => $data['leadtype'],
            'prefix' => $data['prefix'],
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'date' => $data['date'],
            'category' => $data['category'],
            'city' => $data['city'],
            'area' => $data['area'],
            'brancharea' => $data['brancharea'],
            'dncmobile' => $data['dncmobile'],
            'dncphone' => $data['dncphone'],
            'company' => $data['company'],
            'pincode' => $data['pincode'],
            'time' => $data['time'],
            'branchpin' => $data['branchpin'],
            'parentid' => $data['parentid'],
            'lead_source' => $data['lead_source'] ?? 'Unknown',
        ]);

        // Justdialleads::create($data);
        
        
        try {
        Lead::create([
            'name' => $data['name'],
            'contact_info' => $data['mobile'],
            'company' => $data['company'],
            'city' => $data['city'],
            'category' => $data['category'] ?? null,
            'upload_date' => $data['date'],
            'lead_source' => $data['lead_source'] ?? 'Justdial',
        ]);

        // Return a success response
        return response()->json(['message' => 'RECEIVED'], 200);
        }
         catch (\Exception $e) {
        // Log the error for debugging purposes
        \Log::error('Error store Justdial leads: ' . $e->getMessage());

        // Return an internal server error response
        return response()->json(['message' => 'RECEIVED'], 200);
        }

        // Lead::create([
        //     'name' => $data['name'],
        //     'contact_info' => $data['mobile'],
        //     'company' => $data['company'],
        //     'city' => $data['city'],
        //     'upload_date' => $data['date'],
        //     'lead_source' => $data['lead_source'] ?? 'Justdial',
        // ]);

        // Return a success response
        return response()->json(['message' => 'RECEIVED'], 200);
        }
        
        else {
            // Show the import form for GET request
        return response()->json(['message' => 'Only post method Allowed'], 200);
        }
        }
        catch (\Exception $e) {
        // Log the error for debugging purposes
        \Log::error('Error receiving Justdial leads: ' . $e->getMessage());

        // Return an internal server error response
        return response()->json(['message' => 'Internal server error'], 500);
    }
    }
    
    // public function receivejustdialleads(Request $request)
    // {
    //     // Validate incoming request
    //     $data = $request->validate([
    //         'leadid'      => 'required|string|unique:justdialleads,leadid',
    //         'leadtype'    => 'required|string',
    //         'prefix'      => 'nullable|string|max:10',
    //         'name'        => 'required|string|max:255',
    //         'mobile'      => 'required|string|max:50',
    //         'phone'       => 'nullable|string|max:50',
    //         'email'       => 'nullable|string|email|max:255',
    //         'date'        => 'required|date',
    //         'category'    => 'required|string|max:255',
    //         'city'        => 'required|string|max:255',
    //         'area'        => 'nullable|string|max:255',
    //         'brancharea'  => 'nullable|string|max:255',
    //         'dncmobile'   => 'required|boolean',  // Changed from integer
    //         'dncphone'    => 'required|boolean',  // Changed from integer
    //         'company'     => 'required|string|max:255',
    //         'pincode'     => 'nullable|string|max:50',
    //         'time'        => 'required|date_format:H:i:s',
    //         'branchpin'   => 'nullable|string|max:50',
    //         'parentid'    => 'required|string|max:255',
    //         'lead_source' => 'nullable|string|max:255'
    //     ]);

    //     // Check if lead already exists
    //     if (Justdialleads::where('leadid', $data['leadid'])->exists()) {
    //         return response()->json(['message' => 'SUCCESS'], 200);
    //     }

    //     // Store lead data
    //     Justdialleads::create($data);

    //     // Store lead in the Lead table
    //     Lead::create([
    //         'name'        => $data['name'],
    //         'contact_info'=> $data['mobile'],
    //         'city'        => $data['city'],
    //         'upload_date' => $data['date'],
    //         'lead_source' => $data['lead_source'] ?? 'Unknown',
    //     ]);

    //     return response()->json(['message' => 'RECEIVED'], 200);
    // }


    // public function index(Request $request)
    // {
    //     $search = $request->input('search');
    //     $status = $request->input('status');
    //     $priority = $request->input('priority');

    //     $leads = Lead::query();

    //     if ($search) {
    //         $leads->where(function ($query) use ($search) {
    //             $query->where('name', 'like', "%$search%")
    //                 ->orWhere('lead_source', 'like', "%$search%")
    //                 ->orWhere('contact_info', 'like', "%$search%")
    //                 ->orWhere('city', 'like', "%$search%")
    //                 ->get();
    //         });
    //     }

    //     if ($status) {
    //         $leads->where('status', $status);
    //     }

    //     if ($priority) {
    //         $leads->where('priority', $priority);
    //     }

    //     $leads = $leads->paginate(20); // Paginate the results

    //     foreach ($leads as $lead) {
    //         $lead->act_by = json_decode($lead->act_by);
    //     }


    //     return view('admin.leads.index', compact('leads', 'search', 'status', 'priority'));
    // }
    
public function index(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');
    $priority = $request->input('priority');
    
    // Default sort field and direction
    $sortField = $request->input('sort_field', 'created_at');  // Default sorting by 'name'
    $sortDirection = $request->input('sort_direction', 'desc');  // Default sorting direction is ascending

    // Create the base query for leads
    $leads = Lead::query();

    // Apply search filter if provided
    if ($search) {
        $leads->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('lead_source', 'like', "%$search%")
                  ->orWhere('contact_info', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%");
        });
    }

    // Apply status filter if provided
    if ($status) {
        $leads->where('status', $status);
    }

    // Apply priority filter if provided
    if ($priority) {
        $leads->where('priority', $priority);
    }

    // Apply sorting by the selected field and direction
    $leads->orderBy($sortField, $sortDirection);

    // Paginate the results
    $leads = $leads->paginate(10);

    // Decode the 'act_by' field for each lead (if needed)
    foreach ($leads as $lead) {
        $lead->act_by = json_decode($lead->act_by);
    }

    // Return the view with the leads data and sort parameters
    return view('admin.leads.index', compact('leads', 'search', 'status', 'priority', 'sortField', 'sortDirection'));
}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',  // Make sure 'city' is validated
            'category' => 'nullable|string|max:255',
        ]);

        Lead::create($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead added successfully!');
    }

    public function edit(Lead $lead)
    {
        $employees = Employee::where('department', 'Sales')->get();;

        return view('admin.leads.edit', compact('lead', 'employees'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',  // Validate the city field
            'lead_source' => 'nullable|string|max:255',  // Validate the lead source field
            'company' => 'nullable|string|max:255',  // Validate the company field
            'priority' => 'nullable|',  // Validate priority field
            'status' => 'required|',  // Validate status field
            'employee_id' => 'nullable|exists:employees,id', // Validate employee_id to ensure it's an existing employee
            'category' => 'nullable|string|max:255',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully!');
    }
    
public function bulkDestroy(Request $request)
{
    // Retrieve selected lead IDs from the request
    $leadIds = $request->input('selected_leads');

    if ($leadIds) {
        // Bulk delete leads
        Lead::whereIn('id', $leadIds)->delete();

        return response()->json([
            'message' => 'Leads deleted successfully!'
        ]);
    }

    return response()->json([
        'message' => 'No leads selected for deletion.'
    ], 400);
}


public function trackLeadActivities(Request $request)
{
    // Get filter parameters (employee and date)
    $employeeId = $request->input('employee_id');
   
     $search = $request->input('search');
    $status = $request->input('status');
    $priority = $request->input('priority');
    
    // Fetch employees who belong to the Sales department
    $salesEmployees = Employee::where('department', 'Sales')->get();

    // Create the base query for fetching leads
    $leadsQuery = Lead::query();
    
// Apply search filter if provided
    if ($search) {
        $leadsQuery->where(function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('lead_source', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', '%' . $search . '%');
        });
    }

    // Apply status filter if provided
    if ($status) {
        $leadsQuery->where('status', $status);
    }

    
    // Apply employee_id filter if provided
    if ($employeeId) {
        $leadsQuery->where(function($query) use ($employeeId) {
            // Filter where employee_id exists within the act_by array
            $query->whereJsonContains('act_by', [['employee_id' => $employeeId]]);
        });
    }

    // Paginate the results
    $leads = $leadsQuery->paginate(10);

    // Return the filtered leads data along with sales employees
    return view('admin.leads.activity-tracking', compact('leads', 'salesEmployees', 'employeeId'));
}


}
