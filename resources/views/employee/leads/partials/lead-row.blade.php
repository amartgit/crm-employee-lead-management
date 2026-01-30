
    <table class="table table-bordered ">
        <thead class="text-dark">
            <tr class="text-dark" style="font-weight:600;">
                <th class="h6">
                    <a class="h6" href="{{ route('employee.leads.index', array_merge(request()->all(), ['sort_field' => 'updated_at', 'sort_direction' => request('sort_field') == 'updated_at' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
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
                    <a class="h6" href="{{ route('employee.leads.index', array_merge(request()->all(), ['sort_field' => 'id', 'sort_direction' => request('sort_field') == 'id' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
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


                <th class="h6"> Name
                        
                </th>

                <th class="h6">
                    Company
                </th>
                <th class="h6"> Contact Info
                </th>

                <th class="h6"> <a class="h6" href="{{ route('employee.leads.index', array_merge(request()->all(), ['sort_field' => 'lead_source', 'sort_direction' => request('sort_field') == 'lead_source' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
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
                    </a></th>
                <th class="h6"> City

                </th>
<th class="h6">Category</th>

                <th class="h6">Call Status</th>

                <th class="h6">Status</th>
                <th class="h6">Remarks(Actions)</th>
                <th class="h6"> <a class="h6" href="{{ route('employee.leads.index', array_merge(request()->all(), ['sort_field' => 'created_at', 'sort_direction' => request('sort_field') == 'created_at' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                        Upload Date
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
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $index => $lead)
            <tr class="text-dark" style="font-weight:500">
                <td>{{ $leads->firstItem() + $index}}</td>
                <td>{{ $lead->id }}</td>

                <td>{{ $lead->name }}</td>
                <td>{{ $lead->company }}</td>

                <td>{{ $lead->contact_info }}</td>
                <td>{{ $lead->lead_source }}</td>

                <td>{{ $lead->city }}</td>
                <td>{{ $lead->category }}</td>

                <td>
                    @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                    <button type="button" class="btn btn-sm {{ $lead->on_call ? 'btn-danger' : 'btn-success' }}"
                        title="{{ $lead->on_call ? 'Click to end the call' : 'Click to start the call' }}"
                        onclick="toggleOnCall({{ $lead->id }})" id="callButton_{{ $lead->id }}">
                        <i class="fa {{ $lead->on_call ? 'fa-phone-slash' : 'fa-phone' }}"></i>
                        {{ $lead->on_call ? 'On Call' : 'Start Call' }}
                    </button>
                    <!-- Inline message container -->
                    <span id="callStatusMessage_{{ $lead->id }}"></span>
                    @else
                    <span class="badge bg-secondary">Unavailable</span>
                    @endif
                </td>

            

                <td>
                    @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                    <form action="{{ route('leads.updateStatus', $lead->id) }}" method="POST" id="statusForm_{{ $lead->id }}">
                        @csrf
                        <select name="status" class="form-select form-select-sm px-2" style="max-width: 150px;" required onchange="updateStatus({{ $lead->id }})">
                            <option value="" {{ $lead->status === '' ? 'selected' : '' }}>Select</option>
                            <option value="Ringing" {{ $lead->status === 'Ringing' ? 'selected' : '' }}>Ringing</option>
                            <option value="Not Answered" {{ $lead->status === 'Not Answered' ? 'selected' : '' }}>Not Answered</option>
                            <option value="Contact Number Incorrect" {{ $lead->status === 'Contact Number Incorrect' ? 'selected' : '' }}>Contact Number Incorrect</option>
                            <option value="International Number" {{ $lead->status === 'International Number' ? 'selected' : '' }}>International Number</option>
                            <option value="Switched Off" {{ $lead->status === 'Switched Off' ? 'selected' : '' }}>Switched Off</option>
                            <option value="Already Finalized" {{ $lead->status === 'Already Finalized' ? 'selected' : '' }}>Already Finalized</option>
                            <option value="Not Interested" {{ $lead->status === 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                            <option value="Not Required" {{ $lead->status === 'Not Required' ? 'selected' : '' }}>Not Required</option>
                            <option value="If Required Will Connect Us" {{ $lead->status === 'If Required Will Connect Us' ? 'selected' : '' }}>If Required Will Connect Us</option>
                            <option value="Interested, Shared Profile" {{ $lead->status === 'Interested, Shared Profile' ? 'selected' : '' }}>Interested, Shared Profile</option>
                            <option value="Almost Closed" {{ $lead->status === 'Almost Closed' ? 'selected' : '' }}>(Hot Lead)Almost Closed</option>
                            <option value="Closed" {{ $lead->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                            <option value="Other" {{ $lead->status === 'Other' ? 'selected' : '' }}>Other (Specify the reason Add Remark)</option>
                        </select>
                    </form>
                    <span id="statusMessage_{{ $lead->id }}" class="ml-2"></span>
                    @else
                    {{ $lead->status }}
                    @endif
                </td>



                <td>
                    @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                    <form action="{{ route('leads.editActions', $lead->id) }}" method="POST" class="edit-actions-form" data-lead-id="{{ $lead->id }}">
                   
                        @csrf
                        <div class="d-flex gap-2">
                            <textarea name="actions" required class="form-control form-control-sm" rows="2" style="min-width: 150px;">{{ $lead->actions }}</textarea>
                            
                             <span onclick="submitActionForm({{ $lead->id }})" class="btn btn-primary btn-sm">Save</span>
                            <!--<button type="submit" class="btn btn-primary btn-sm">Save</button>-->
                        </div>
                        <!-- Inline message container for feedback -->
                        <span class="action-status-message ml-2"></span>
                    </form>
                    @else
                    {{ $lead->actions }}
                    @endif
                </td>

                <td>{{ $lead->upload_date }} - {{ $lead->created_at->format('h:i:s A') }}</td>



            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No leads available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
