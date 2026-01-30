@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Leads Management</h2>
                <p class="text-muted">Track Lead Activities</p>
            </div>
        </div>
    </div>
<div class="container">

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.leads.track-activities') }}" class="mb-4">
        <div class="row py-1">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="employee_id">Employee Name(See Leads For Respective Employee)</label>
                    <select name="employee_id" id="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($salesEmployees as $employee)
                            <option value="{{ $employee->employee_id }}" 
                                {{ request('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                {{ $employee->fname }} {{ $employee->lname }} ({{ $employee->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            
        </div>
                
        <button type="submit" class="btn btn-primary">Filter</button>

 <div class="row py-1 form-group">
           
            <div class="col-md-6">
                <p>Filter for leads</p>
                <input type="text" name="search" class="form-control mx-2 mb-2" placeholder="Search leads by sourcename " 
                    value="{{ request('search') }}">
                    
                     <!-- Filter by Status -->
                <select name="status" class="form-control mx-2 mb-2">
                    <option value="">Filter by Status</option>
                    <option value="Ringing" {{ request('status') === 'Ringing' ? 'selected' : '' }}>Ringing</option>
                    <option value="Not Answered" {{ request('status') === 'Not Answered' ? 'selected' : '' }}>Not Answered</option>
                    <option value="Contact Number Incorrect" {{ request('status') === 'Contact Number Incorrect' ? 'selected' : '' }}>Contact Number Incorrect</option>
                    <option value="International Number" {{ request('status') === 'International Number' ? 'selected' : '' }}>International Number</option>
                    <option value="Switched Off" {{ request('status') === 'Switched Off' ? 'selected' : '' }}>Switched Off</option>
                    <option value="Already Finalized" {{ request('status') === 'Already Finalized' ? 'selected' : '' }}>Already Finalized</option>
                    <option value="Not Interested" {{ request('status') === 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="Not Required" {{ request('status') === 'Not Required' ? 'selected' : '' }}>Not Required</option>
                    <option value="If Required Will Connect Us" {{ request('status') === 'If Required Will Connect Us' ? 'selected' : '' }}>If Required Will Connect Us</option>
                    <option value="Interested, Shared Profile" {{ request('status') === 'Interested, Shared Profile' ? 'selected' : '' }}>Interested, Shared Profile</option>
                    <option value="Almost Closed" {{ request('status') === 'Almost Closed' ? 'selected' : '' }}>Almost Closed</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Other" {{ request('status') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
</div>
            
    </form>

    <hr>

    <!-- Activity Table -->
    <div class="table-responsive py-2">
        @if($leads->isNotEmpty())
            <table class="table table-striped table-bordered text-dark" style="font-weight:600;">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th>Lead Name</th>
                        <th>Lead Source</th>
                        <td >Current Status</td>

                        <th>Activities  ({{ $employeeId ? $salesEmployees->firstWhere('employee_id', $employeeId)->fname . ' ' . $salesEmployees->firstWhere('employee_id', $employeeId)->lname : 'All Employees' }}) </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leads as $lead)
                        @php
                            // Decode the 'act_by' JSON data to get activities
                            $activities = json_decode($lead->act_by);
                            
                            // Filter activities by the selected employee
                            $filteredActivities = collect($activities)->filter(function($activity) use ($employeeId) {
                                return $activity->employee_id == $employeeId;
                            });
                        @endphp
                        
                        @if($filteredActivities->isNotEmpty())
                            <tr>
                                <td class="align-middle">{{ $lead->id }}</td>
                                <td class="align-middle">{{ $lead->name }}</td>
                                <td class="align-middle">{{ $lead->lead_source }}</td>
                                <td class="align-middle">{{ $lead->status }}</td>

                                <!-- Display activities -->
                                <td>
                                    <div style="max-height: 300px; overflow-y: auto; font-size: 12px; padding: 10px; border: 1px solid #ddd;">
                                        @foreach($filteredActivities as $activity)
                                            <div class="activity">
                                                <p>
                                                    
                                                    @if(isset($activity->status) && $activity->status !== null)
                                                        <strong>Status:</strong> "{{ $activity->status }}" - 
                                                        <span class="text-end">{{ \Carbon\Carbon::parse($activity->timestamp)->format('l, F j, Y g:i A') }}</span>
                                                    @elseif(isset($activity->on_call))
                                                        <strong>Call:</strong> {{ $activity->on_call ? 'START' : 'END' }} - 
                                                        <span class="text-end">{{ \Carbon\Carbon::parse($activity->timestamp)->format('l, F j, Y g:i A') }}</span>
                                                    @elseif(isset($activity->actions) && $activity->actions !== null && $activity->actions !== '')
                                                        <strong>Remark:</strong> "{{ $activity->actions }}" - 
                                                        <span class="text-end">{{ \Carbon\Carbon::parse($activity->timestamp)->format('l, F j, Y g:i A') }}</span>
                                                    @else
                                                        
                                                    @endif
                                                </p>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="pagination-container text-center mt-4">
                {{ $leads->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>

        @else
            <p>No activities found for the selected filters.</p>
        @endif
    </div>
</div>
</div>
@endsection
