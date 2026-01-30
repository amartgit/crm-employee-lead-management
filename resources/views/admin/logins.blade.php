@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Dashboard</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>

                <h2 class="pt-2">Daily Logins</h2>
            </div>
        </div>
    </div>

    <div class="container py-2">
        <h3 class="mb-2" >Employee Login Records</h3>

<div class="p-1">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.logins') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="employee_id" class="form-control mb-2" placeholder="Employee ID" value="{{ request('employee_id') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control mb-2" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <select name="month" class="form-control mb-2">
                        <option value="">Select Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date("F", mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary m-2">Filter</button>
                    <a href="{{ route('admin.logins') }}" class="btn btn-secondary  m-2">Reset</a>
                </div>
            </div>
        </form>
</div>
        <!-- Login Records Table -->
        <div class="table-responsive mb-3">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr No</th>

                    <th>Employee ID</th>
                    <th>Employee Name</th>

                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logins as $index => $login)
                    <tr>
                        <td>{{ $logins->firstItem() + $index }}</td>

                        <td>{{ $login->employee_id }}</td>
                        <td>{{ $login->employee->fname ?? 'N/A' }} {{ $login->employee->lname ?? 'N/A' }}</td>

                        <td>{{ $login->login_time ? \Carbon\Carbon::parse($login->logout_time)->format('M d, Y h:i A') : 'N/A' }}</td>
                        <!--<td>{{ $login->logout_time ?? 'N/A' }}</td>-->
                        <td>{{ $login->logout_time ? \Carbon\Carbon::parse($login->logout_time)->format('M d, Y h:i A') : 'N/A' }}</td>

                            <!--<td>-->
                            <!--    @if($login->logout_time)-->
                            <!--        {{ $login->logout_time }}-->
                            <!--    @else-->
                            <!--        <span class="text-warning">N/A (Session Expired?)</span>-->
                            <!--    @endif-->
                            <!--</td>-->
                        <td>{{ $login->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $logins->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
