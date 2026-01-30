@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Monthly Attendance </h2>


            </div>
        </div>
    </div>

    <div class="container">
        <h1>Monthly Attendance for {{ $employee->fname }} {{ $employee->lname }} - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h1>
        <p>Total Working Hours: {{ number_format($totalMonthlyHours, 2) }} hours</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Working Hours</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : 'N/A' }}</td>
                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : 'N/A' }}</td>
                    <td>{{ $attendance->total_working_time }}</td>
                    <td>{{ $attendance->status }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.daily', ['employee_id' => $employee->id, 'date' => $attendance->date]) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
