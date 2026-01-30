@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Edit Profile</h2>
                <p class="text-muted">Updating profile for: <strong>{{ $employee->fname }}</strong></p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="row g-4">
            @csrf

            <div class="col-md-4 mb-2">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control alpha-only" value="{{ old('fname', $employee->fname) }}" required>
            </div>

            <div class="col-md-4  mb-2">
                <label for="mname" class="form-label">Middle Name</label>
                <input type="text" name="mname" class="form-control alpha-only" value="{{ old('mname', $employee->mname) }}" >
            </div>

            <div class="col-md-4  mb-2">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control alpha-only" value="{{ old('lname', $employee->lname) }}" required>
            </div>

            <div class="col-md-6  mb-2">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" name="phone_number" minlength="10" maxlength="10" class="form-control number-only" placeholder="Enter 10 digit phone number" value="{{ old('phone_number', $employee->phone_number) }}" required>
            </div>

            <div class="col-md-6  mb-2">
                <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" minlength="10" maxlength="10" class="form-control number-only" placeholder="Enter 10 digit phone number" value="{{ old('whatsapp_number', $employee->whatsapp_number) }}">
            </div>

            <div class="col-md-6  mb-2">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $employee->dob) }}">
            </div>

            <div class="col-md-6  mb-2">
                <label for="gender" class="form-label">Gender</label><br>
                <select name="gender" class="form-control">
                    <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-6  mb-2">
                <label for="mailid" class="form-label">Email</label>
                <input type="email" name="mailid" class="form-control" value="{{ old('mailid', $employee->mailid) }}">
            </div>

            <div class="col-md-6  mb-2">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="1">{{ old('address', $employee->address) }}</textarea>
            </div>
            <div class="col-md-6  mb-2">
                <label for="blood_group" class="form-label">Blood Group</label>
                <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $employee->blood_group) }}">
            </div>
            <div class="col-md-6  mb-2">
                <label for="Emergency_contact" class="form-label">Emergency Contact</label>
                <input type="tel" name="Emergency_contact" minlength="10" maxlength="10" class="form-control number-only" placeholder="Enter 10 digit phone number" value="{{ old('Emergency_contact', $employee->Emergency_contact) }}">
            </div>

            <div class="col-12  mb-2">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
