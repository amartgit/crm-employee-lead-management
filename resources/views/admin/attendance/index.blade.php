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
 <form method="GET" action="{{ route('admin.attendance.monthly') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="month" class="form-label">Select Month (Able To Add Missing attedance )</label>
                    <input type="month" name="month" value="{{ request('month') ?? now()->format('Y-m') }}" class="form-control" id="month">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" title="View attendance for selected month">View</button>
                </div>
            </div>
        </form>
    <!-- Success/Error Alerts -->
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

    <!-- Date Filter Shortcuts -->
    <div class="btn-group mb-3">

         <!-- "Yesterday" Button -->
    <a href="{{ route('admin.attendance.index', ['date_filter' => 'yesterday']) }}" 
       class="btn btn-secondary {{ request('date_filter') == 'yesterday' ? 'btn-success' : '' }}">Yesterday</a>

    <!-- "Today" Button -->
    <a href="{{ route('admin.attendance.index', ['date_filter' => 'today']) }}" 
       class="btn btn-secondary {{ request('date_filter') == 'today' ? 'btn-success' : '' }}">Today</a>

    <!-- "Last Week" Button -->
    <a href="{{ route('admin.attendance.index', ['date_filter' => 'last_week']) }}" 
       class="btn btn-secondary {{ request('date_filter') == 'last_week' ? 'btn-success' : '' }}">Last Week</a>
    </div>

    <!-- Employee ID Filter and Pagination Controls -->
<form method="GET" action="{{ route('admin.attendance.index') }}">
    <div class="row mb-3">
        <!-- Employee ID Filter -->
        <div class="col-md-3 py-2">
            <label for="employee_id" class="form-label">Employee ID</label>
            <input type="text" name="employee_id" value="{{ request('employee_id') }}" class="form-control" placeholder="Search by Employee ID">
        </div>

        <!-- Month Filter -->
        <div class="col-md-3 py-2">
            <label for="month" class="form-label">Select Month</label>
            <input type="month" name="month" value="{{ request('month') ?? now()->format('Y-m') }}" class="form-control" id="month">
        </div>

        <!-- Records Per Page Control -->
        <div class="col-md-2">
            <label for="per_page" class="form-label">Records per page</label>
            <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>

        <div class="col-md-1 d-flex align-items-end g-4">
            <button type="submit" class="btn btn-primary w-100 mx-10">Search</button>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary w-100 mx-10">reset</a>

        </div>
    </div>
</form>

    <!-- Attendance Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th> <a class="h6" href="{{ route('admin.attendance.index', array_merge(request()->all(), ['sort_field' => 'employee_id', 'sort_direction' => request('sort_field') == 'employee_id' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                        Employee ID
                        @if(request('sort_field') == 'employee_id')
                            @if(request('sort_direction') == 'asc')
                                <i class="fas fa-sort-up"></i>
                            @else
                                <i class="fas fa-sort-down"></i>
                            @endif
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a></th>
                    <th>Employee Name</th>
                    <th> <a class="h6" href="{{ route('admin.attendance.index', array_merge(request()->all(), ['sort_field' => 'date', 'sort_direction' => request('sort_field') == 'date' && request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}">
                Date
                @if(request('sort_field') == 'date')
                    @if(request('sort_direction') == 'asc')
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                @else
                    <i class="fas fa-sort"></i>
                @endif
            </a></th>
                    <th>Check In</th>
                    <th>Check Out</th>
                                        <th>Total Working Time</th>

                    <th>Status</th>
                    <!--<th>Verified</th>-->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->employee_id }}</td>
                        <td>{{ $attendance->employee->fname ?? 'N/A' }} {{ $attendance->employee->lname ?? 'N/A' }}</td>
                        <td>{{ $attendance->date ? $attendance->date->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $attendance->check_in ? $attendance->check_in->format('Y-m-d H:i A') : 'N/A' }}</td>
                        <td>{{ $attendance->check_out ? $attendance->check_out->format('Y-m-d H:i A') : 'N/A' }}</td>
                 <td>
    @if($attendance->check_in && $attendance->check_out)
        @php
            $diffInSeconds = $attendance->check_in->diffInSeconds($attendance->check_out, false);
            $diffInSeconds = abs($diffInSeconds);
            $hours = floor($diffInSeconds / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);
        @endphp
        {{ $hours }}h {{ $minutes }}m 
    @else
        N/A
    @endif
</td>
                        <td><select class="form-control attendance-status" data-id="{{ $attendance->id }}">
        <option value="Present" {{ $attendance->status == 'Present' ? 'selected' : '' }}>Present</option>
        <option value="Halfday" {{ $attendance->status == 'Halfday' ? 'selected' : '' }}>Half Day</option>

        <option value="Absent" {{ $attendance->status == 'Absent' ? 'selected' : '' }}>Absent</option>
        <option value="Leave" {{ $attendance->status == 'Leave' ? 'selected' : '' }}>Leave</option>
        <option value="Holiday" {{ $attendance->status == 'Holiday' ? 'selected' : '' }}>Holiday</option>
    </select>
    <span id="statusMessage_{{ $attendance->id }}"></span>
                            </td>
                        <!--<td>{{ $attendance->verified ? 'Verified' : 'Unverified' }}</td>-->
                        <td>
                            <button class="btn btn-warning btn-sm"
                                    data-toggle="modal" data-target="#editAttendanceModal{{ $attendance->id }}">
                                Edit
                            </button>                           
                           <button 
                class="btn btn-sm {{ $attendance->verified ? 'btn-success' : 'btn-warning' }}"
                onclick="toggleVerification({{ $attendance->id }})"
                data-attendance-id="{{ $attendance->id }}">
                {{ $attendance->verified ? 'Unverify' : 'Verify' }}
            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="pagination-container">
        {{ $attendances->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>

</div>

<!-- Edit Attendance Modals -->
@foreach($attendances as $attendance)
<div class="modal fade" id="editAttendanceModal{{ $attendance->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Attendance for {{ $attendance->employee_id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="feedback_{{ $attendance->id }}"></div>
                <div data-attendance-date="{{ $attendance->id }}">
                {{ $attendance->date ? $attendance->date->format('Y-m-d') : 'N/A' }}
            </div>

            <form id="attendanceForm{{ $attendance->id }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Check In (m/d/y T)</label>
                        <input type="datetime-local" class="form-control" name="check_in"
                               id="check_in_{{ $attendance->id }}" 
                               value="{{ $attendance->check_in ? $attendance->check_in->format('Y-m-d\TH:i') : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Check Out (m/d/y T)</label>
                        <input type="datetime-local" class="form-control" name="check_out"
                               id="check_out_{{ $attendance->id }}"
                               value="{{ $attendance->check_out ? $attendance->check_out->format('Y-m-d\TH:i') : '' }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateAttendance" data-id="{{ $attendance->id }}">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // AJAX function to toggle attendance verification
    function toggleVerification(id) {
        $.ajax({
            url: '{{ route("admin.attendance.toggleVerification", ":id") }}'.replace(':id', id), // Replace :id with the actual attendance ID
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Optionally update the button text or class based on the response
                    //alert(response.message); // You can replace this with a more elegant notification.
                    
                    // Find the button for the specific attendance ID and toggle its class
                    const button = $('button[data-attendance-id="' + id + '"]');
                    if (button.hasClass('btn-warning')) {
                        button.removeClass('btn-warning').addClass('btn-success');
                        button.text('Unverify');
                    } else {
                        button.removeClass('btn-success').addClass('btn-warning');
                        button.text('Verify');
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while processing the request.');
            }
        });
    }
</script>
<script>
$(document).ready(function () {
    $('.attendance-status').change(function () {
        let attendanceId = $(this).data('id');
        let newStatus = $(this).val();
        let statusMessage = $("#statusMessage_" + attendanceId);

        $.ajax({
            url: "/admin/attendance/" + attendanceId + "/update-status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: newStatus
            },
            beforeSend: function () {
                statusMessage.html('<span class="text-info">Updating...</span>');
            },
            success: function (response) {
                statusMessage.html('<span class="text-success">Updated!</span>');
                setTimeout(function () {
                    statusMessage.html('');
                }, 2000);
            },
            error: function (xhr) {
                  let statusMessage = $("#statusMessage_" + attendanceId);
    // let responseText = xhr.responseText;
    // console.error("AJAX Error:", responseText); // Logs error to the browser console
    statusMessage.html('<span class="text-danger">Error: ' + responseText + '</span>');
                statusMessage.html('<span class="text-danger">Error updating!</span>');
            }
        });
    });
});
</script>

<script>
$(document).ready(function () {
    $('.updateAttendance').on('click', function () {
        let attendanceId = $(this).data('id');
        let checkInInput = $('#check_in_' + attendanceId);
        let checkOutInput = $('#check_out_' + attendanceId);
        let feedbackContainer = $('#feedback_' + attendanceId);
        let submitBtn = $(this);

        let attendanceDate = $('div[data-attendance-date="'+attendanceId+'"]').text().trim(); // Extract the date from the modal

        feedbackContainer.html(""); // Clear previous messages

        let checkInValue = checkInInput.val();
        let checkOutValue = checkOutInput.val();

        if (!checkInValue || !checkOutValue) {
            feedbackContainer.html('<div class="alert alert-danger">Both Check-in and Check-out times are required!</div>');
            return;
        }

        let checkIn = new Date(checkInValue);
        let checkOut = new Date(checkOutValue);

        // Ensure check-in and check-out are on the same day
        if (checkIn.toDateString() !== checkOut.toDateString()) {
            feedbackContainer.html('<div class="alert alert-warning">Check-in and Check-out must be on the same day!</div>');
            return;
        }

        // Ensure check-in and check-out match the attendance date
        if (checkIn.toISOString().split('T')[0] !== attendanceDate) {
            feedbackContainer.html('<div class="alert alert-danger">Check-in and Check-out must match the attendance date (' + attendanceDate + ')!</div>');
            return;
        }

        if (checkOut <= checkIn) {
            feedbackContainer.html('<div class="alert alert-warning">Check-out time must be after Check-in time!</div>');
            return;
        }

        submitBtn.prop('disabled', true); // Disable button to prevent multiple clicks

        $.ajax({
            url: "{{ route('admin.attendance.update', '') }}/" + attendanceId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "PUT",
                check_in: checkInValue,
                check_out: checkOutValue,
            },
            success: function (response) {
                feedbackContainer.html('<div class="alert alert-success">Attendance updated successfully!</div>');
                setTimeout(function () {
                    $('#editAttendanceModal' + attendanceId).modal('hide');
                    // location.reload();
                }, 1500);
            },
            error: function (xhr) {
                feedbackContainer.html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
            },
            complete: function () {
                submitBtn.prop('disabled', false); // Re-enable button after response
            }
        });
    });
});

</script>

@endpush

@endsection
