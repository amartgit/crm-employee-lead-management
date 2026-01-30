<table>
    <tr><th colspan="2">📋 All Leads Status Summary Overview</th></tr>
    <tr><td><strong>Total Leads</strong></td><td><strong>{{ $totalLeads }}</strong></td></tr>
    @foreach($statusCounts as $status => $count)
        <tr>
            <td>{{ $status ?: 'No Status' }}</td>
            <td>{{ $count }}</td>
        </tr>
    @endforeach
</table>

<br><br>

<table>
    <tr><th colspan="2">📅 Daily Call Count ({{ $selectedDate }})</th></tr>
    <tr><th>Employee</th><th>Calls Made</th></tr>
    @foreach($dailyCalls as $employee => $count)
        <tr>
            <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
            <td>{{ $count }}</td>
        </tr>
    @endforeach
</table>

<br><br>

<table>
    <tr><th colspan="2">🗓️ Monthly Call Count ({{ $selectedMonth }})</th></tr>
    <tr><th>Employee</th><th>Calls Made</th></tr>
    @foreach($monthlyCalls as $employee => $count)
        <tr>
            <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
            <td>{{ $count }}</td>
        </tr>
    @endforeach
</table>

<br><br>

<table>
    <tr>
        <th colspan="{{ collect($dailyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique()->count() + 1 }}">
            📊 Daily Lead Status Count by Employee ({{ $selectedDate }})
        </th>
    </tr>
    <tr>
        <th>Employee</th>
        @php $allDailyStatuses = collect($dailyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique(); @endphp
        @foreach($allDailyStatuses as $status)
            <th>{{ $status }}</th>
        @endforeach
    </tr>
    @foreach($dailyStatusCounts as $employee => $statuses)
        <tr>
            <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
            @foreach($allDailyStatuses as $status)
                <td>{{ $statuses[$status] ?? 0 }}</td>
            @endforeach
        </tr>
    @endforeach
</table>

<br><br>

<table>
    <tr>
        <th colspan="{{ collect($monthlyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique()->count() + 1 }}">
            📈 Monthly Lead Status Count by Employee ({{ $selectedMonth }})
        </th>
    </tr>
    <tr>
        <th>Employee</th>
        @php $allMonthlyStatuses = collect($monthlyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique(); @endphp
        @foreach($allMonthlyStatuses as $status)
            <th>{{ $status }}</th>
        @endforeach
    </tr>
    @foreach($monthlyStatusCounts as $employee => $statuses)
        <tr>
            <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
            @foreach($allMonthlyStatuses as $status)
                <td>{{ $statuses[$status] ?? 0 }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
