@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Attendance Management</h2>


            </div>
        </div>
    </div>

<div class="container-fluid">

    <div class="card mb-4">
        <div class="card-body">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <h5 class="card-title">Today's Attendance</h5>
            <p class="card-text">Date: {{ now()->format('l, Y-m(F)-d') }}</p>
            <p class="card-text">Employee: {{ $employee->fname }} {{ $employee->lname }}</p>

            @php
            $todayAttendance = $attendances->first(function($attendance) {
            return $attendance->date->isToday();
            });
            @endphp

            @if($todayAttendance && $todayAttendance->check_in)
            <p class="card-text">Check-in: {{ $todayAttendance->check_in->format('Y-m-F H:i A') }}</p>

            @if($todayAttendance->check_out)
            <p class="card-text">Check-out: {{ $todayAttendance->check_out->format('Y-m-F H:i A') }}</p>
        @php
            // Calculate total working time
            $checkInTime = \Carbon\Carbon::parse($todayAttendance->check_in);
            $checkOutTime = \Carbon\Carbon::parse($todayAttendance->check_out);
            $totalWorkingMinutes = $checkInTime->diffInMinutes($checkOutTime); // Get total minutes
            $totalWorkingHours = floor($totalWorkingMinutes / 60); // Get full hours
            $remainingMinutes = $totalWorkingMinutes % 60; // Get the remaining minutes
        @endphp
        
        <p class="card-text">Total Working Time: {{ $totalWorkingHours }} hours {{ $remainingMinutes }} minutes</p>            @else
            <!-- Log Break Form -->
            <!--<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#logBreakModal{{ $todayAttendance->id }}">-->
            <!--    Add/Take Break-->
            <!--</button>-->
            <!--<br><br>-->
            <form action="{{ route('attendance.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">Check Out</button>
            </form>
            @endif
            @else
            <form action="{{ route('attendance.checkin') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="work_type" class="form-label">Work Type</label>
                    <select name="work_type" id="work_type" class="form-control" required>
                        <option value="">Select Work Type</option>
                        <option value="WFH">WFH</option>
                        <option value="Office">Office</option>
                        <option value="OnSite">OnSite</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Check In</button>
            </form>
            @endif
        </div>
    </div>

    <h3 class="mb-3">Attendance History</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="bg-dark text-light">
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Work Type</th>
                    <th>Notes</th>
                    <th>Actions</th>
                    <th>Verified</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date->format('l, Y-m-d') }}</td>
                    <td>{{ $attendance->check_in ? $attendance->check_in->format('Y-m-d H:i A') : 'Pending' }}</td>
                    <td>{{ $attendance->check_out ? $attendance->check_out->format('Y-m-d H:i A') : 'Pending' }}</td>
                    <td>{{ $attendance->work_type ?? 'N/A' }}</td>
                    <td>{{ $attendance->notes ?? 'No notes' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#noteModal{{ $attendance->id }}">
                            Add Note
                        </button>
                    </td>
                    <td class="{{ $attendance->verified ? 'bg-success text-white' : 'bg-danger text-white' }}">
                        {{ $attendance->verified ? 'Yes' : 'No' }}
                    </td>
                    <td>{{ $attendance->status ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

 <div class="pagination-container">
        {{ $attendances->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>

    @foreach($attendances as $attendance)
    <!-- Modal for Adding Notes -->
    <div class="modal fade" id="noteModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="noteModalLabel{{ $attendance->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel{{ $attendance->id }}">Add Note for {{ $attendance->date->toDateString() }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('attendance.addnote', $attendance->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $attendance->notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Logging Break -->
    <!--<div class="modal fade" id="logBreakModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="logBreakModalLabel{{ $attendance->id }}" aria-hidden="true">-->
    <!--    <div class="modal-dialog">-->
    <!--        <div class="modal-content">-->
    <!--            <div class="modal-header">-->
    <!--                <h5 class="modal-title" id="logBreakModalLabel{{ $attendance->id }}">Log Break for {{ $attendance->date->toDateString() }}</h5>-->
    <!--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
    <!--            </div>-->
    <!--            <form action="{{ route('attendance.logBreak', $attendance->id) }}" method="POST">-->
    <!--                @csrf-->
    <!--                <div class="modal-body">-->
    <!--                    <div class="mb-3">-->
    <!--                        <label for="break_type" class="form-label">Break Type</label>-->
    <!--                        <select class="form-control" id="break_type" name="break_type" required>-->
    <!--                            <option value="">Select Break Type</option>-->
    <!--                            <option value="Lunch">Lunch</option>-->
    <!--                            <option value="Coffee">Coffee</option>-->
    <!--                            <option value="Other">Other</option>-->
    <!--                        </select>-->
    <!--                    </div>-->
    <!--                    <div class="mb-3">-->
    <!--                        <label for="start_time" class="form-label">Start Time</label>-->
    <!--                        <input type="time" class="form-control" id="start_time" name="start_time" required>-->
    <!--                    </div>-->
    <!--                    <div class="mb-3">-->
    <!--                        <label for="end_time" class="form-label">End Time</label>-->
    <!--                        <input type="time" class="form-control" id="end_time" name="end_time" required>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="modal-footer">-->
    <!--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
    <!--                    <button type="submit" class="btn btn-primary">Save Break</button>-->
    <!--                </div>-->
    <!--            </form>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    @endforeach
</div>
</div>
@endsection
