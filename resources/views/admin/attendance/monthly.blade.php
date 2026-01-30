@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Monthly Attendance Management</h2>

            </div>
        </div>
    </div>

<div class="container">

    <!-- Success and Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Select Month and Year -->
        <div class="p-1">
    <form method="GET" action="{{ route('admin.attendance.monthly') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="month" class="form-label">Select Month</label>
                <input type="month" name="month" value="{{ request('month') ?? now()->format('Y-m') }}" class="form-control" id="month">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" title="View attendance for selected month">View</button>
            </div>
        </div>
    </form>
            </div>
     <div class="p-1">
            <a href="{{ route('admin.attendance.monthSummary') }}" class="btn btn-primary "> Month Summary</a>
        </div>

    <hr>

    <!-- Display Monthly Attendance Table -->
    @if(request('month'))
    <h3 class="text-center mb-4">Attendance for {{ \Carbon\Carbon::parse(request('month'))->format('F, Y') }}</h3>
    <p>(Able To Add Missing attedance(Dark Background) by Chaging Status To Present And then Edit )</p>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr >
                    <th class="sticky-col">Employee ID</th>
                    @php
                    $daysInMonth = \Carbon\Carbon::parse(request('month'))->daysInMonth;
                    @endphp
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        <th class="text-center sticky-header">{{ $day }}</th>
                        @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td class="sticky-col">{{ $employee->employee_id }} <br>{{ $employee->fname }} {{ $employee->lname }}</td>
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                        $date=\Carbon\Carbon::parse(request('month'))->setDay($day);
                        $attendance = $employee->attendances()->whereDate('date', $date)->first();
                        @endphp
                        <td class="text-center
                        ">
                            @if($attendance)
                            <form action="{{ route('admin.attendance.update-status', $attendance->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status"
                                    class="form-select  @if($attendance->status == 'Present')
                        text-success
                    @elseif($attendance->status == 'Halfday')
                        text-success
                    @elseif($attendance->status == 'Absent')
                        text-danger
                    @elseif($attendance->status == 'Leave')
                        text-warning
                    @elseif($attendance->status == 'Holiday')
                        text-info
                    @else
                        text-dark
                    @endif
                    " onchange="this.form.submit()">
                                    <option value="pending" {{$attendance->status == 'pending' ? 'selected' : '' }}>pending</option>
                                    <option value="Present" {{ $attendance->status == 'Present' ? 'selected' : '' }}>Present</option>
                                    <option value="Halfday" {{ $attendance->status == 'Halfday' ? 'selected' : '' }}>Halfday</option>

                                    
                                    <option value="Absent" {{ $attendance->status == 'Absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="Leave" {{ $attendance->status == 'Leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="Holiday" {{ $attendance->status == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                                </select>
                            </form>
                            @else
                            <form action="{{ route('admin.attendance.store', [$employee->employee_id, $date->format('Y-m-d')]) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select text-light bg-dark" onchange="this.form.submit()">
                                    <option value="pending">pending</option>
                                    <option value="Present">Present</option>
                                    <option value="Halfday">Half Day</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Leave">Leave</option>
                                    <option value="Holiday">Holiday</option>
                                </select>
                            </form>
                            @endif
                        </td>
                        @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</div>
<!-- Add the sticky column style -->
<style>
 /* Make the Employee ID column sticky */
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: #000;
        color: #fff;
        z-index: 2; /* Ensures the employee column stays above other columns */
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.6); /* Optional: adds a slight shadow effect */
    }

    /* Make the header row sticky */
    .sticky-header {
        position: sticky;
        top: 0;
        background-color: #343a40;
        color: #fff;
        z-index: 1; /* Ensures headers stay on top */
    }

    /* Optional: Style the table and make it scrollable */
    .table-responsive {
        max-height: 500px; /* Adjust this to the desired height */
        overflow-y: auto;
    }
</style>
@endsection
