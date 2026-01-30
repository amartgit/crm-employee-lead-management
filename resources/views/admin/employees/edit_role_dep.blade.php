@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2 class="pt-2">Edit Employee Role & Department</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Edit Role and Department for {{ $employee->fname }} {{ $employee->lname }}</h3>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Role and Department Edit Form -->
        <form action="{{ route('admin.employees.updateRoleDep', $employee->employee_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Role Field with Dropdown (disabled for SuperAdmin) -->
            <div class="form-group">
                <label for="role" class="text-primary">Role</label>
               <!--<select class="form-control" name="role" id="role" -->
               <!--     {{ $employee->user->role == 'SuperAdmin' ? 'disabled' : '' }} required>-->
               <!--     <option value="Admin" {{ old('role', $employee->user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>-->
               <!--      <option value="Employee" {{ old('role', $employee->user->role) == 'Employee' ? 'selected' : '' }}>Employee</option>-->
               <!--     </select>-->
               <!-- @if($employee->user->role == 'SuperAdmin')-->
               <!-- <small class="form-text text-muted">Role cannot be changed for Superadmin.</small>-->
               <!-- @endif-->
               
                <select class="form-control" name="role" id="role" 
                        {{ $employee->user->role == 'SuperAdmin' || (auth()->user()->employee_id === $employee->employee_id && auth()->user()->role === 'Admin') ? 'disabled' : '' }} required>
                    <option value="Admin" {{ old('role', $employee->user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Employee" {{ old('role', $employee->user->role) == 'Employee' ? 'selected' : '' }}>Employee</option>
                </select>

                @if($employee->user->role == 'SuperAdmin')
                    <small class="form-text text-muted">Role cannot be changed for Superadmin.</small>
                @elseif(auth()->user()->employee_id === $employee->employee_id && auth()->user()->role === 'Admin')
                    <small class="form-text text-muted">Admin role cannot be changed for your own account.</small>
                @endif
            </div>

            <!-- Department Field with Dropdown -->
            <div class="form-group">
                <label for="department" class="text-primary">Department</label>
            <!--    <select class="form-control" name="department" id="department" -->
            <!--            {{ $employee->user->role == 'SuperAdmin' ? 'disabled' : '' }} required>-->
            <!--        <option value="HR" {{ old('department', $employee->department) == 'HR' ? 'selected' : '' }}>HR</option>-->
            <!--        <option value="IT" {{ old('department', $employee->department) == 'IT' ? 'selected' : '' }}>IT</option>-->
            <!--        <option value="Sales" {{ old('department', $employee->department) == 'Sales' ? 'selected' : '' }}>Sales</option>-->
            <!--        <option value="Manager" {{ old('department', $employee->department) == 'Manager' ? 'selected' : '' }}>Manager</option>-->
            <!--        <option value="User" {{ old('department', $employee->department) == 'User' ? 'selected' : '' }}>User</option>-->
            <!--        <option value="Finance - Accounts" {{ old('department', $employee->department) == 'Finance - Accounts' ? 'selected' : '' }}>Finance - Accounts</option>-->
            <!--        <option value="Production" {{ old('department', $employee->department) == 'Production' ? 'selected' : '' }}>Production</option>-->
            <!--        <option value="Designer" {{ old('department', $employee->department) == 'Designer' ? 'selected' : '' }}>Designer</option>-->
            <!--    </select>-->
            <!--@if($employee->user->role == 'SuperAdmin')-->
            <!--<small class="form-text text-muted">Department cannot be changed for Superadmin.</small>-->
            <!--@endif-->
            
            <select class="form-control" name="department" id="department" 
                        {{ $employee->user->role == 'SuperAdmin' || (auth()->user()->employee_id === $employee->employee_id && auth()->user()->role === 'Admin') ? 'disabled' : '' }} required>
                    <option value="HR" {{ old('department', $employee->department) == 'HR' ? 'selected' : '' }}>HR</option>
                    <option value="IT" {{ old('department', $employee->department) == 'IT' ? 'selected' : '' }}>IT</option>
                    <option value="Sales" {{ old('department', $employee->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Manager" {{ old('department', $employee->department) == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="User" {{ old('department', $employee->department) == 'User' ? 'selected' : '' }}>User</option>
                    <option value="Finance - Accounts" {{ old('department', $employee->department) == 'Finance - Accounts' ? 'selected' : '' }}>Finance - Accounts</option>
                    <option value="Production" {{ old('department', $employee->department) == 'Production' ? 'selected' : '' }}>Production</option>
                    <option value="Designer" {{ old('department', $employee->department) == 'Designer' ? 'selected' : '' }}>Designer</option>
                </select>

                @if($employee->user->role == 'SuperAdmin')
                    <small class="form-text text-muted">Department cannot be changed for Superadmin.</small>
                @elseif(auth()->user()->employee_id === $employee->employee_id && auth()->user()->role === 'Admin')
                    <small class="form-text text-muted">Admin department cannot be changed for your own account.</small>
                @endif

            </div>

            <!-- Submit and Cancel Buttons -->
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
