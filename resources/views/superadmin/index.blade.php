@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2>Dashboard</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>
                <h2 class="pt-2">Super Admin Dashboard</h2>

                @if(session('error'))
                <div class="p-2">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Access Denied!</strong><br>
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>  
                        </button>  
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row column1">
        <!-- Attendance -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('attendance.dashboard') }}" class="full counter_section margin_bottom_30 hover:border">
                <div class="couter_icon">
                    <i class="fa fa-clock-o yellow_color" data-toggle="tooltip" title="Click to view the my attendance "></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Attendance</p>
                </div>
            </a>
        </div>

        <!-- Profile -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('profile.show') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fa fa-user yellow_color" data-toggle="tooltip" title="View your profile"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">My Profile</p>
                </div>
            </a>
        </div>
        
        <!-- Leave Request Shortcut Card -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('leave.create') }}" class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div>
                            <i class="fa fa-clock-o yellow_color" data-toggle="tooltip" title="Request leaves"></i>
                        </div>
                    </div>
                    <div class="counter_no">
                        <div>
                            <p class="head_couter">Request leaves</p>
                        </div>
                    </div>
                </a>
            </div>

 <!-- Change Password -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('password.change') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fa fa-key yellow_color" data-toggle="tooltip" title="Change your password"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">Change Password</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Employee Management -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('superadmin.employees.index') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fa fa-users yellow_color" data-toggle="tooltip" title="Manage employees"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Employees</p>
                </div>
            </a>
        </div>

        <!-- Daily Logins -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.logins') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-download yellow_color" data-toggle="tooltip" title="View daily logins"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Daily Logins</p>
                </div>
            </a>
        </div>

        <!-- Manage Roles -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('superadmin.employees.index') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-user-cog yellow_color" data-toggle="tooltip" title="Manage employee roles and departments"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Manage Roles</p>
                </div>
            </a>
        </div>

        <!-- Register New Employee -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('register.show') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-user-plus yellow_color" data-toggle="tooltip" title="Register a new employee"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Register Employee</p>
                </div>
            </a>
        </div>

        <!-- Sales Reports -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.salesreports.index') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-chart-pie yellow_color" data-toggle="tooltip" title="View sales reports"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Sales Reports</p>
                </div>
            </a>
        </div>

        <!-- Import Leads -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.leads.import') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-file-upload yellow_color" data-toggle="tooltip" title="Import new leads"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Import Leads</p>
                </div>
            </a>
        </div>

        <!-- Lead Activities -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.leads.track-activities') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-clipboard-list yellow_color" data-toggle="tooltip" title="Track lead activities"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Lead Activities</p>
                </div>
            </a>
        </div>

        <!-- All Leads -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.leads.index') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-address-book yellow_color" data-toggle="tooltip" title="View all leads"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">All Leads</p>
                </div>
            </a>
        </div>

        <!-- Justdial Leads -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('admin.leads.justdialleads') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <i class="fas fa-search-location yellow_color" data-toggle="tooltip" title="View Justdial leads"></i>
                </div>
                <div class="counter_no">
                    <p class="head_couter">Justdial Leads</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
