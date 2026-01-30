@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Lead Management</h2>
                <p class="pt-2"> Leads Activity</p>
            </div>
        </div>
    </div>
    <div class="container p-2">
        
            <form method="GET" action="{{ route('employee.leads.my_activity') }}" class="mb-4">


 <div class="row py-1 form-group">
         
            <div class="col-md-6">
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
                    <option value="Almost Closed" {{ request('status') === 'Almost Closed' ? 'selected' : '' }}>(Hot Lead)Almost Closed</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Other" {{ request('status') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
</div>
                    <button type="submit" class="btn btn-primary">Filter</button>

    </form>


        @if($leads->isEmpty())
            <p>You haven't worked on any leads yet.</p>
        @else
        
        <div class="table-responsive mb-3">
            <table class="table table-bordered">
                <thead class="text-dark" style="font-weight:600;">
                  

                        <th>Lead ID</th>
                        <th>Status</th>
                        <th ><a class="h6" href="{{ route('employee.leads.my_activity', array_merge(request()->all(), [
    'sort_field' => 'updated_at', 
    'sort_direction' => request('sort_field') == 'updated_at' && request('sort_direction') == 'asc' ? 'desc' : 'asc'
])) }}">Activity History
                        @if(request('sort_field') == 'updated_at')
        @if(request('sort_direction') == 'asc')
            <i class="fas fa-sort-up"></i>
        @else
            <i class="fas fa-sort-down"></i>
        @endif
    @else
        <i class="fas fa-sort"></i>
    @endif
                     </a>   </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-dark" style="font-weight:600;">
                    @foreach ($leads as $lead)

                            <td>{{ $lead->id }}</td>
                            <td>{{ $lead->status }}</td>
                            <td >
                                <ul style="max-height: 100px; overflow-y: scroll; font-size:12px;">
                                    @foreach (json_decode($lead->act_by, true) as $action)
                                        <li>
                                            <!-- Check if the 'status' key exists and is not null -->
                                            @if(isset($action['status']) && $action['status'] !== '')
                                                <p><strong>Status:</strong> "{{ $action['status'] }}"</p>
                                            @else
                                                <p><strong>Status:</strong> </p>
                                            @endif

                                            <!-- Check if the 'on_call' key exists -->
                                            @if(isset($action['on_call']))
                                                <p><strong> Call:</strong> {{ $action['on_call'] ? 'START' : 'END' }}</p>
                                            @endif

                                            <!-- Check if the 'actions' key exists -->
                                            @if(isset($action['actions']) && $action['actions'] !== null)
                                                <p><strong>Actions:</strong> "{{ $action['actions'] }}"</p>
                                            @endif

                                            <!-- Display the timestamp in a readable format -->
                                            <p><strong>Timestamp:</strong> {{ \Carbon\Carbon::parse($action['timestamp'])->format('d-m-Y H:i A') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <!-- Button to redirect with the Lead ID as filter -->
                                <a href="{{ route('employee.leads.index', ['search' => $lead->id]) }}" class="btn btn-primary"> <i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="pagination-container p-2 d-flex justify-content-center">
    {{ $leads->appends(request()->all())->links('pagination::bootstrap-5') }}
</div>
        @endif
    </div>
</div>
@endsection
