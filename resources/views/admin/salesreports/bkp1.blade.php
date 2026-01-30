@extends('layouts.app')

@section('content')
<div class="container-fluid p-1">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="pt-2">Sales Reports</h2>
            </div>
        </div>
    </div>

    <div class="container p-2">

        <h3>📋 Current All Leads Status Summary Overview</h3>
        <div class="p-3 shadow-sm ">
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Total Leads</strong></td>
                            <td><strong>{{ $totalLeads }}</strong></td>
                        </tr>
                        @foreach($statusCounts as $status => $count)
                            <tr>
                                <td>{{ $status ?: 'No Status' }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-4">
<div>
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-3">
                <label class="form-label">Select Date (Daily Calls)</label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Select Month (YYYY-MM)</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button class="btn btn-primary">🔍 Filter</button>
            </div>
        </form>

        <h5 class="mt-4">📅 Daily Call Count ({{ $selectedDate }})</h5>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Employee</th><th>Calls Made</th></tr></thead>
            <tbody>
                @forelse($dailyCalls as $employee => $count)
                    <tr>
                        <td>{{ $employee }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">No calls made on this day.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h5 class="mt-4">🗓️ Monthly Call Count ({{ $selectedMonth }})</h5>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Employee</th><th>Calls Made</th></tr></thead>
            <tbody>
                @forelse($monthlyCalls as $employee => $count)
                    <tr>
                        <td>{{ $employee }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">No calls made in this month.</td></tr>
                @endforelse
            </tbody>
        </table>
</div>

<div>
   <h5 class="mt-5">📊 Daily Lead Status Count by Employee ({{ $selectedDate }})</h5>
    <div class="table-responsive mb-3">
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Employee</th>
            @php
                $allDailyStatuses = collect($dailyStatusCounts)->flatMap(function($s) {
                    return array_keys($s);
                })->unique()->values();
            @endphp
            @foreach($allDailyStatuses as $status)
                <th>{{ $status }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($dailyStatusCounts as $employee => $statuses)
            <tr>
                <td>{{ $employee }}</td>
                @foreach($allDailyStatuses as $status)
                    <td>{{ $statuses[$status] ?? 0 }}</td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="{{ $allDailyStatuses->count() + 1 }}">No status updates on this day.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

<h5 class="mt-5">📈 Monthly Lead Status Count by Employee ({{ $selectedMonth }})</h5>
 <div class="table-responsive mb-3">
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Employee</th>
            @php
                $allMonthlyStatuses = collect($monthlyStatusCounts)->flatMap(function($s) {
                    return array_keys($s);
                })->unique()->values();
            @endphp
            @foreach($allMonthlyStatuses as $status)
                <th>{{ $status }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($monthlyStatusCounts as $employee => $statuses)
            <tr>
                <td>{{ $employee }}</td>
                @foreach($allMonthlyStatuses as $status)
                    <td>{{ $statuses[$status] ?? 0 }}</td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="{{ $allMonthlyStatuses->count() + 1 }}">No status updates in this month.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("admin.salesreports.index") }}',
            type: 'GET',
            data: $(this).serialize(),
            success: function(data) {
                $('#callResults').html($(data).find('#callResults').html());
            }
        });
    });
</script>
@endsection
