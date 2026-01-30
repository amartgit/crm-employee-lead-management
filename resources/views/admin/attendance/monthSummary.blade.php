@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Monthly Attendance Report</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="p-1">
        <form method="GET" action="{{ route('admin.attendance.monthSummary') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control" id="month">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" title="View attendance for selected month">View</button>
                </div>
            </div>
        </form>
        </div>
  
        <hr>

        <!-- Display the total days in the selected month -->
        <div class="row mt-3">
            <div class="col-md-3">
                <div id="total_days">Total Days in Month {{ Carbon\Carbon::parse($month)->daysInMonth }}</div>
            </div>
        </div>
        <!-- Monthly Attendance Summary Table -->
        <div class="table-responsive p-2" style="max-height:80vh;">
            <style>
                /* Sticky Header Class */
        .sticky-header {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 0;
            z-index: 1; /* Ensure the header stays above the table content */
            background-color: #fff; /* White background for clarity */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional shadow */
        }
            </style>
            <table class="table table-bordered"  >
                <thead class="sticky-header">
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Present Days</th>
                        <th>Half Days</th>

                        <th>Leave Days</th>
                        <th>Holiday Days</th>
                        <th>Absent Days</th>
                        <!--<th>Total Working Hours</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceSummary as $summary)
                    <tr>
                        <td>{{ $summary['employee_id'] }}</td>
                        <td>{{ $summary['employee_name'] }}</td>
                        <td>{{ $summary['present_days'] }}</td>
                            <td>{{ $summary['Half_days'] }}</td>

                        <td>{{ $summary['leave_days'] }}</td>
                        <td>{{ $summary['holiday_days'] }}</td>
                        <td>{{ $summary['absent_days'] }}</td>
                      <!--  <td>{{ gmdate('H:i', $summary['total_working_minutes']) }}</td>  Display in hours and minutes -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
<!--    <div>-->
<!--        <hr>-->
<!--<h4 class="mt-4">Monthly Attendance Chart</h4>-->
<!--<canvas id="attendanceChart" height="120"></canvas>-->

<!--    </div>-->
    <div class="col-md-2 d-flex align-items-end">
    <a href="{{ route('admin.attendance.exportMonthly', ['month' => $month]) }}" class="btn btn-success w-100">
        Export to Excel
    </a>
</div>

</div>
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<!--<script>-->
<!--    const ctx = document.getElementById('attendanceChart').getContext('2d');-->

<!--    const labels = {!! json_encode(array_column($attendanceSummary, 'employee_name')) !!};-->
<!--    const presentData = {!! json_encode(array_column($attendanceSummary, 'present_days')) !!};-->
<!--    const halfData = {!! json_encode(array_column($attendanceSummary, 'Half_days')) !!};-->
<!--    const leaveData = {!! json_encode(array_column($attendanceSummary, 'leave_days')) !!};-->
<!--    const holidayData = {!! json_encode(array_column($attendanceSummary, 'holiday_days')) !!};-->
<!--    const absentData = {!! json_encode(array_column($attendanceSummary, 'absent_days')) !!};-->

<!--    new Chart(ctx, {-->
<!--        type: 'bar',-->
<!--        data: {-->
<!--            labels: labels,-->
<!--            datasets: [-->
<!--                {-->
<!--                    label: 'Present',-->
<!--                    data: presentData,-->
<!--                    backgroundColor: 'rgba(75, 192, 192, 0.8)'-->
<!--                },-->
<!--                {-->
<!--                    label: 'Half Day',-->
<!--                    data: halfData,-->
<!--                    backgroundColor: 'rgba(255, 206, 86, 0.8)'-->
<!--                },-->
<!--                {-->
<!--                    label: 'Leave',-->
<!--                    data: leaveData,-->
<!--                    backgroundColor: 'rgba(255, 99, 132, 0.8)'-->
<!--                },-->
<!--                {-->
<!--                    label: 'Holiday',-->
<!--                    data: holidayData,-->
<!--                    backgroundColor: 'rgba(153, 102, 255, 0.8)'-->
<!--                },-->
<!--                {-->
<!--                    label: 'Absent',-->
<!--                    data: absentData,-->
<!--                    backgroundColor: 'rgba(255, 159, 64, 0.8)'-->
<!--                }-->
<!--            ]-->
<!--        },-->
<!--        options: {-->
<!--            responsive: true,-->
<!--            scales: {-->
<!--                x: {-->
<!--                    stacked: true-->
<!--                },-->
<!--                y: {-->
<!--                    beginAtZero: true,-->
<!--                    stacked: true-->
<!--                }-->
<!--            }-->
<!--        }-->
<!--    });-->
<!--</script>-->

@endsection
