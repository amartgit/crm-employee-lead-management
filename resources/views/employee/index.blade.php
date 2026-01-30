@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Dashboard</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>
                <h2 class="pt-2">Employee Dashboard</h2>
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
        <!-- Profile -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('profile.show') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fa fa-user yellow_color" data-toggle="tooltip" title="View your profile"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="total_no"></p>
                        <p class="head_couter">My Profile</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Attendance -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('attendance.dashboard') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fa fa-clock-o yellow_color" data-toggle="tooltip" title="View attendance details"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">Attendance</p>
                    </div>
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

        <!-- Edit Profile -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('profile.edit') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fa fa-edit yellow_color" data-toggle="tooltip" title="Edit your profile"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">Edit Profile</p>
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

        <!-- Sales-related Sections (Conditional for Sales Department) -->
        @if (Auth::user()->employee->department === 'Sales')

        <!-- Leads -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('employee.leads.index') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fa fa-users yellow_color" data-toggle="tooltip" title="View all leads"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">Leads</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- My Leads -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('employee.leads.myleads') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fas fa-list yellow_color" data-toggle="tooltip" title="View your assigned leads"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">My Leads</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- My Lead Activity -->
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('employee.leads.my_activity') }}" class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div>
                        <i class="fas fa-chart-line yellow_color" data-toggle="tooltip" title="Track your lead activity"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="head_couter">My Lead Activity</p>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
</div>

@endsection


