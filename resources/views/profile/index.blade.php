@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Profile</h2>
                <p class="text-muted">Welcome, <strong>{{ $employee->fname }}</strong></p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5">Your Profile Details</h3>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <p class="h4"><strong>ID:</strong> {{ $user->employee_id }}</p>
                        
                    </div>
                    <div class="col-md-6">
                       
                        <p><strong>First Name:</strong> {{ $employee->fname }}</p>
                        <p><strong>Middle Name:</strong> {{ $employee->mname }}</p>
                        <p><strong>Last Name:</strong> {{ $employee->lname }}</p>
                        <p><strong>Email:</strong> {{ $employee->mailid }}</p>

                    </div>
                    <div class="col-md-6">
                        <p><strong>Phone Number:</strong> {{ $employee->phone_number }}</p>
                        <p><strong>WhatsApp Number:</strong> {{ $employee->whatsapp_number }}</p>
                        <p><strong>Emergency Contact:</strong> {{ $employee->Emergency_contact }}</p>
                        <p><strong>Blood Group:</strong> {{ $employee->blood_group }}</p>

                        <p><strong>Gender:</strong> {{ $employee->gender }}</p>
                    </div>
                    <div class="col-md-12">
                        <p><strong>Address:</strong> {{ $employee->address }}</p>
                        <p><strong>Date of Birth:</strong> {{ $employee->dob }}</p>
                        <p><strong>Department:</strong> {{ $employee->department }}</p>

                    </div>
                    
                    
                </div>
                
                 <div class="mt-3">
                    <a href="{{ route('password.change') }}" class="btn btn-warning">Change Password</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
