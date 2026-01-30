@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Leads Management</h2>
                <p class="text-muted">Welcome, <strong>{{ Auth::user()->name }}</strong>!</p>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Contact Info</th>
                        <th>City</th>

                        <th>Status</th>
                        <th>Priority</th>
                        <th>Call Status</th>
                        <th>Actions</th>
                        <th>Upload_date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                    <tr id="lead-row-{{ $lead->id }}">
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->contact_info }}</td>
                        <td id="status-{{ $lead->id }}">
                            @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                            <form action="{{ route('leads.updateStatus', $lead->id) }}" method="POST" id="lead-status-form-{{ $lead->id }}">
                                @csrf
                                <select name="status" class="form-select form-select-sm" onchange="submitForm('{{ $lead->id }}')">
                                    <option value="Interested" {{ $lead->status === 'Interested' ? 'selected' : '' }}>Interested</option>
                                    <option value="Closed" {{ $lead->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="Not Interested" {{ $lead->status === 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                    <option value="DND" {{ $lead->status === 'DND' ? 'selected' : '' }}>DND</option>
                                    <option value="Other" {{ $lead->status === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </form>
                            @else
                            {{ $lead->status }}
                            @endif
                        </td>
                        <td>{{ ucfirst($lead->priority) }}</td>
                        <td id="call-status-{{ $lead->id }}">
                            @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                            <form action="{{ route('leads.toggleOnCall', $lead->id) }}" method="POST" id="call-status-form-{{ $lead->id }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $lead->on_call ? 'btn-danger' : 'btn-success' }}" title="{{ $lead->on_call ? 'Click to end the call' : 'Click to start the call' }}">
                                    <i class="fa {{ $lead->on_call ? 'fa-phone-slash' : 'fa-phone' }}"></i>
                                    {{ $lead->on_call ? 'On Call' : 'Start Call' }}
                                </button>
                            </form>
                            @else
                            <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            @if ($lead->employee_id === Auth::id() || !$lead->employee_id)
                            <form action="{{ route('leads.editActions', $lead->id) }}" method="POST">
                                @csrf
                                <textarea name="actions" class="form-control form-control-sm" rows="2">{{ $lead->actions }}</textarea>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                            </form>
                            @else
                            {{ $lead->actions }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No leads available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $leads->links() }}
        </div>
    </div>
</div>

@foreach ($leads as $lead)

<script>
    // Submit form when status changes
    function submitForm(leadId) {
        document.getElementById('lead-status-form-' + leadId).submit();
    }
    // Listen to real-time updates using Laravel Echo
    window.Echo.private('leads.' + {{ $lead->id }})
        .listen('LeadUpdated', (event) => {
            if (event && event.lead) {
                const lead = event.lead;
                const leadRow = document.getElementById('lead-row-' + lead.id);
                if (leadRow) {
                    // Update Status
                    const statusCell = leadRow.querySelector('[id^="status-"]');
                    if (statusCell) {
                        statusCell.textContent = lead.status;
                    }

                    // Update Call Status
                    const callButton = leadRow.querySelector('button');
                    if (callButton) {
                        if (lead.on_call) {
                            callButton.classList.remove('btn-success');
                            callButton.classList.add('btn-danger');
                            callButton.innerHTML = '<i class="fa fa-phone-slash"></i> On Call';
                        } else {
                            callButton.classList.remove('btn-danger');
                            callButton.classList.add('btn-success');
                            callButton.innerHTML = '<i class="fa fa-phone"></i> Start Call';
                        }
                    }

                    // Update Actions
                    const actionsCell = leadRow.querySelector('[id^="actions-"]');
                    if (actionsCell) {
                        actionsCell.innerHTML = lead.actions;
                    }
                }
            }
        });
</script>
@endforeach

@endsection
