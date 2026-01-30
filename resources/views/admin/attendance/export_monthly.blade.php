<table>
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold;">
                Monthly Attendance Report for {{ \Carbon\Carbon::parse($month)->format('F Y') }}
            </th>
        </tr>
        <tr></tr> <!-- spacer row -->

        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Present Days</th>
            <th>Half Days</th>
            <th>Leave Days</th>
            <th>Holiday Days</th>
            <th>Absent Days</th>
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
        </tr>
        @endforeach
    </tbody>
</table>
