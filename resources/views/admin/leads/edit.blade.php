@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <h1>Edit Lead</h1>

    <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Lead Name</label>
            <input type="text" class="form-control alpha-only" id="name" name="name" value="{{ old('name', $lead->name) }}" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" class="form-control number-only" id="contact_info" name="contact_info" value="{{ old('contact_info', $lead->contact_info) }}"required>
        </div>
        <div class="form-group">
            <label for="company">company</label>
            <input type="text" class="form-control"  id="company" name="company" value="{{ old('company', $lead->company) }}"required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $lead->city) }}">
        </div>


        <div class="form-group">
            <label for="lead_source">Lead Source</label>
            <input type="text" class="form-control" id="lead_source" name="lead_source" value="{{ old('lead_source', $lead->lead_source) }}">
        </div>
        
         <div class="form-group">
            <label for="priority">Priority</label>
            <select class="form-control" id="priority" name="priority" >
                <option value="low" {{ old('priority', $lead->priority) == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority', $lead->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority', $lead->priority) == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" >
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
<option value="Other" {{ $lead->status === 'Other' ? 'selected' : '' }}>Other</option>

            </select>
        </div>
        
                <div class="form-group">
    <label for="employee_id">Lead Assigned to Sales Department Employee(leave blank for unassign it will remove from Employee my List)</label>
    <select class="form-control" id="employee_id" name="employee_id">
        <option value="">Select Employee </option>
        @foreach ($employees as $employee)
            <option value="{{ $employee->id }}" 
                {{ old('employee_id', $lead->employee_id) == $employee->id ? 'selected' : '' }}>
                {{ $employee->fname }}
                {{ $employee->employee_id }}
            </option>
        @endforeach
    </select>
</div>
        <button type="submit" class="btn btn-success">Update Lead</button>
    </form>
</div>
@endsection
