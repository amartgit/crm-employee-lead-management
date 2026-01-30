@extends('layouts.app')

@section('content')
<div class="container-fluid p-2">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="pt-2">Sales Reports</h2>
            </div>
        </div>
    </div>

    <div class="container p-4 bg-white shadow rounded-3 mb-4">
        <h3 class="fw-bold mb-3">📋 Current All Leads Status Summary Overview</h3>

        <div class="table-responsive shadow-sm rounded bg-light p-3 mb-4">
            <table class="table table-bordered  table-striped ">
                <thead class="table-secodary text-center">
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

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Select Date (Daily Calls)</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Select Month (YYYY-MM)</label>
                        <input type="month" name="month" value="{{ $selectedMonth }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div id="callResults" class="p-2 bg-white rounded shadow-sm">
            <!-- Daily Call Count -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2">📅 Daily Call Count ({{ $selectedDate }})</h5>
                <div class="table-responsive shadow-sm rounded bg-light p-2">
                    <table class="table table-bordered  ">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Calls Made</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyCalls as $employee => $count)
                            <tr>
                                <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">No calls made on this day.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Call Count -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2">🗓️ Monthly Call Count ({{ $selectedMonth }})</h5>
                <div class="table-responsive shadow-sm rounded bg-light p-2">
                    <table class="table table-bordered  ">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Calls Made</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyCalls as $employee => $count)
                            <tr>
                                <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">No calls made in this month.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daily Lead Status -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2">📊 Daily Lead Status Count by Employee ({{ $selectedDate }})</h5>
                <div class="table-responsive shadow-sm rounded bg-light p-2">
                    <table class="table table-bordered  ">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                @php
                                    $allDailyStatuses = collect($dailyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique()->values();
                                @endphp
                                @foreach($allDailyStatuses as $status)
                                <th>{{ $status }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyStatusCounts as $employee => $statuses)
                            <tr>
                                <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
                                @foreach($allDailyStatuses as $status)
                                <td>{{ $statuses[$status] ?? 0 }}</td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $allDailyStatuses->count() + 1 }}">No status updates on this day.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Lead Status -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2">📈 Monthly Lead Status Count by Employee ({{ $selectedMonth }})</h5>
                <div class="table-responsive shadow-sm rounded bg-light p-2">
                    <table class="table table-bordered  ">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                @php
                                    $allMonthlyStatuses = collect($monthlyStatusCounts)->flatMap(fn($s) => array_keys($s))->unique()->values();
                                @endphp
                                @foreach($allMonthlyStatuses as $status)
                                <th>{{ $status }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyStatusCounts as $employee => $statuses)
                            <tr>
                                <td>{{ $getEmployeeName($employee) }} (ID: {{ $employee }})</td>
                                @foreach($allMonthlyStatuses as $status)
                                <td>{{ $statuses[$status] ?? 0 }}</td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $allMonthlyStatuses->count() + 1 }}">No status updates in this month.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('admin.salesreports.export', ['date' => $selectedDate, 'month' => $selectedMonth]) }}"
               class="btn btn-success">
                📥 Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("admin.salesreports.index") }}',
                type: 'GET',
                data: $(this).serialize(),
                success: function (response) {
                    const newContent = $(response).find('#callResults').html();
                    $('#callResults').html(newContent);
                },
                error: function (xhr) {
                    console.error('AJAX error:', xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
