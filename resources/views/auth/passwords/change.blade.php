@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Password Update</h2>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title mb-3">
                    <h2 class="display-6">Change Password</h2>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.updateemp') }}" >
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-3">
                            <p>Use Min 6 character</p>

                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" minlength="6" id="current_password" class="form-control" required>
                            @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" minlength="6" id="new_password" class="form-control" required>
                            @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" minlength="6" id="new_password_confirmation" class="form-control" required>
                            @error('new_password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center py-2">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
