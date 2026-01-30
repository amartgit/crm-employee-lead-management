<!-- resources/views/admin/attendance/show.blade.php -->

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Attendance for {{ $employee->name }}</h2>


            </div>
        </div>
    </div>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Total Working Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date->format('d-m-Y') }}</td>
                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : 'N/A' }}</td>
                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'N/A' }}</td>
                    <td>{{ $attendance->total_working_time }} hours</td>
                    <td>{{ $attendance->verified ? 'Verified' : 'Unverified' }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @if(!$attendance->verified)
                            <form action="{{ route('admin.attendance.verify', $attendance->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Verify</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $attendances->links('pagination::bootstrap-5') }} <!-- Pagination -->
</div>
</div>
@endsection
