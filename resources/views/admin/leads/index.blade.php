@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Lead Management</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>

                <h2 class="pt-2">Admin Dashboard Lead Management</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>All Leads</h2>

        <br>
        <div class="">
        <a href="#" class="btn btn-primary mb-3 text-white" data-toggle="modal" data-target="#addLeadModal">Add New Lead</a> 
         <a href="{{ route('admin.leads.justdialleads') }}" class="btn btn-secondary mb-3">View Justdial Leads</a> 
         <a href="" class="btn btn-secondary mb-3">View IndiaMart Leads</a> 

        <a href="{{ route('admin.leads.track-activities') }}" class="btn btn-primary mb-3 text-white">Track Lead/Emp</a>
          <a href="{{ route('admin.salesreports.index') }}" class="btn btn-primary mb-3 text-white">Sales Lead Reports</a>
</div>
<div class="p-2">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="form-inline mb-4">
            <input type="text" name="search" class="form-control mx-2 mb-2" placeholder="Search leads by source,name, city,"
                value="{{ request('search') }}">

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
<option value="Almost Closed" {{ request('status') === 'Almost Closed' ? 'selected' : '' }}>(Hot Lead)Almost Closed</option>
<option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
<option value="Other" {{ request('status') === 'Other' ? 'selected' : '' }}>Other</option>

            </select>

            <select name="priority" class="form-control mx-2 mb-2">
                <option value="">Select Priority</option>
                <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
            </select>

            <button type="submit" class="btn btn-primary mx-2"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">Reset</a>

        </form>
</div>

        @if($leads->isEmpty())
            <div class="alert alert-warning p-2">
                No leads available to display.
            </div>
        @else
            <div class="table-responsive mb-3">
                <p>Click On Heading Tabs For Sort Leads Asc/Desc order</p>
                <table class="table table-bordered">
                    <!--<thead class="bg-dark text-light">-->
                    <!--    <tr>-->
                    <!--        <th>SR</th>-->
                    <!--        <th>Name</th>-->
                    <!--        <th>Company</th>-->

                    <!--        <th>Contact Info</th>-->
                    <!--        <th>City</th>-->
                    <!--        <th>Source</th>-->
                    <!--        <th>Priority</th>-->
                    <!--        <th>Status</th>-->
                    <!--        <th>Remarks</th>-->
                    <!--        <th>Closed By</th>-->
                    <!--        <th>acted by</th>-->
                    <!--    </tr>-->
                    <!--</thead>-->
                    
                    <thead class="">
                        <tr class="text-dark" style="font-weight:600;">
                            <th class="h6">
                                <a class="h6" href="{{ route('admin.leads.index', array_merge(request()->all(), ['sort_field' => 'updated_at', 'sort_direction' => request('sort_field') == 'updated_at' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                    SR
                                    @if(request('sort_field') == 'updated_at')
                                        @if(request('sort_direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>

                            <th class="h6">
                                <a class="h6" href="{{ route('admin.leads.index', array_merge(request()->all(), ['sort_field' => 'created_at', 'sort_direction' => request('sort_field') == 'created_at' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Created At
                                    @if(request('sort_field') == 'created_at')
                                        @if(request('sort_direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                           <th class="h6">
    <a class="h6" href="{{ route('admin.leads.index', array_merge(request()->all(), ['sort_field' => 'id', 'sort_direction' => request('sort_field') == 'id' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
        Lead ID
        @if(request('sort_field') == 'id')
            @if(request('sort_direction') == 'asc')
                <i class="fas fa-sort-up"></i>
            @else
                <i class="fas fa-sort-down"></i>
            @endif
        @else
            <i class="fas fa-sort"></i>
        @endif
    </a>
</th>

                            <th class="h6">
                                <a class="h6" href="{{ route('admin.leads.index', array_merge(request()->all(), ['sort_field' => 'name', 'sort_direction' => request('sort_field') == 'name' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Name
                                    @if(request('sort_field') == 'name')
                                        @if(request('sort_direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="h6">
                                Company
                            </th>
                            <th class="h6">
                                Contact Info

                            </th>
                            <th class="h6">
                                City

                              
                            </th>
   <th class="h6">Category</th>

                            <th class="h6">
                                <a class="h6" href="{{ route('admin.leads.index', array_merge(request()->all(), ['sort_field' => 'lead_source', 'sort_direction' => request('sort_field') == 'lead_source' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Source
                                    @if(request('sort_field') == 'lead_source')
                                        @if(request('sort_direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                           
                            <th class="h6">Status</th>
                            
                            <th class="h6">Acted By</th>
                            <th class="h6">Action</th>
                            <th class="h6">Closed By</th>

                             <th class="h6">
                                Priority
                               
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $index => $lead)
                            <tr class="text-dark" style="font-weight:600;">
                                <td>{{ $leads->firstItem() + $index }}</td>
                                <td>{{ $lead->created_at->format('Y-m-d H:i:s A') }}</td>
                                <td>{{ $lead->id }}</td>

                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->company }}</td>

                                <td>{{ $lead->contact_info }}</td>
                                <td>{{ $lead->city }}</td>
<td>{{ $lead->category ?? 'N/A' }}</td>

                                <td>{{ $lead->lead_source }}</td>

                               
                                <td>{{ $lead->status }}</td>
                               
                               
                                <!--<td>-->
                                <!--    <div style="max-height: 100px; overflow-y: scroll; font-size:12px;">-->
                                <!--        @if ($lead->act_by && count($lead->act_by) > 0)-->
                                <!--            @foreach ($lead->act_by as $act)-->
                                <!--                @foreach ($act as $key => $value)-->
                                <!--                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>-->
                                <!--                    @if (is_bool($value))-->
                                <!--                        {{ $value ? 'Yes' : 'No' }}-->
                                <!--                    @elseif (is_string($value))-->
                                <!--                        {{ $value }}-->
                                <!--                    @elseif ($value instanceof \Carbon\Carbon)-->
                                <!--                        {{ $value->toDateTimeString() }}-->
                                <!--                    @else-->
                                <!--                        {{ $value }}-->
                                <!--                    @endif-->
                                <!--                    <br>-->
                                <!--                @endforeach-->
                                <!--                <hr>-->
                                <!--            @endforeach-->
                                <!--        @else-->
                                <!--            <p>No actions recorded.</p>-->
                                <!--        @endif-->
                                <!--    </div>-->
                                <!--</td>-->
                                <td >
    <div style="max-height: 100px; min-width:200px; overflow-y: scroll; font-size:12px;">
        @if ($lead->act_by && count($lead->act_by) > 0)
            @foreach ($lead->act_by as $act)
                @foreach ($act as $key => $value)
                    @if ($key === 'timestamp' && $value)
                        <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                        {{ \Carbon\Carbon::parse($value)->format('l, F j, Y g:i A') }}
                        <br>
                    @elseif ($key !== 'timestamp')
                        <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                        @if (is_bool($value))
                            {{ $value ? 'Yes' : 'No' }}
                        @elseif (is_string($value))
                            {{ $value }}
                        @elseif ($value instanceof \Carbon\Carbon)
                            {{ $value->toDateTimeString() }}
                        @else
                            {{ $value }}
                        @endif
                        <br>
                    @endif
                @endforeach
                <hr>
            @endforeach
        @else
            <p>No actions recorded.</p>
        @endif
    </div>
</td>
                                <td>
                                    <a href="{{ route('admin.leads.edit', $lead->id) }}" class="btn btn-warning p-1 px-3">
                                        <i class="fas fa-edit text-success"></i>
                                    </a>
                                    <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger p-1 px-3 mt-1" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                                <td>
                                    @if ($lead->employee)
                                        {{ $lead->employee->id }} -
                                        {{ $lead->employee->fname }} -
                                        {{ $lead->employee->employee_id }}
                                    @else
                                        Unassigned
                                    @endif
                                </td>
                                <td>
                                    @if ($lead->priority == 'Low')
                                        Low
                                    @elseif ($lead->priority == 'Medium')
                                        Medium
                                    @elseif ($lead->priority == 'High')
                                        High
                                    @else
                                        NA
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

<div class="pagination-container">
    {{ $leads->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
</div>

    </div>

</div>

<!-- Add Lead Modal (make sure to define this modal) -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#9e2f88; color:white;">
                <h5 class="modal-title text-white" id="addLeadModalLabel">Add New Lead</h5>
                <button type="button" class="bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-window-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form for adding a new lead here -->
                <form method="POST" action="{{ route('admin.leads.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Lead Name</label>
                        <input type="text" name="name" class="form-control alpha-only" placeholder="Enter Lead Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_info" class="form-label">Contact Info</label>
                        <input type="text" name="contact_info" class="form-control number-only" placeholder="Enter Contact Info" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Enter City">
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="Enter Category" required>
                    </div>


                    <input type="hidden" class="form-control" id="status" name="status" value="pending">
                    <input type="hidden" value="manual" id="lead_source" name="lead_source">
                    
                    <button type="submit" class="btn btn-primary my-2">Save Lead</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection