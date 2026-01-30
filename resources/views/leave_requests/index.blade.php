@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title mb-3">
                    <h2>My Leave Requests</h2>
                </div>
                <div class="container">
                    <a href="{{ route('leave.create') }}" class="btn btn-primary m-2">New Leave Request</a>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Dates</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $leave)
                                <tr>
                                    <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                                    <td>{{ $leave->leave_type }}</td>
                                    <td>{{ $leave->reason ?? '-' }}</td>
                                    <td>
                                        @php
                                            switch ($leave->status) {
                                                case 'Approved':
                                                    $badgeClass = 'success';
                                                    break;
                                                case 'Rejected':
                                                    $badgeClass = 'danger';
                                                    break;
                                                case 'Pending':
                                                default:
                                                    $badgeClass = 'warning';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge text-white bg-{{ $badgeClass }}">
                                            {{ $leave->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No leave requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
