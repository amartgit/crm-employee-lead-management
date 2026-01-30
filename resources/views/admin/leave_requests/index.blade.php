@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title mb-3">
                    <h2>All Leave Requests</h2>
                </div>

                {{-- Filter Form --}}
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label>Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $request->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->fname }} {{ $employee->lname }} - {{ $employee->employee_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $request->start_date }}">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $request->end_date }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>

                {{-- Alert --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Table --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Dates</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                            <tr>
                                <td>{{ $leave->employee->fname ?? 'N/A' }} {{ $leave->employee->lname ?? '' }} - {{ $leave->employee->employee_id ?? '' }}</td>
                                <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                                <td>{{ $leave->leave_type }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    <span class="badge text-light bg-{{ $leave->status === 'Approved' ? 'success' : ($leave->status === 'Rejected' ? 'danger' : 'warning') }}">
                                        {{ $leave->status }}
                                    </span>
                                </td>
                                <td>
                                    {{-- @if($leave->status === 'Pending') --}}
                                        <form method="POST" action="{{ route('admin.leave.approve', $leave->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leave.reject', $leave->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    {{-- @else
                                        <em>No actions</em>
                                    @endif --}}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No leave requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
@endsection
