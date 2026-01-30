@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2 class="pt-2">Edit Employee Access</h2>
            </div>
        </div>
    </div>
    <div class="container">
   <h2>Edit Permissions for : {{ $employee->employee_id }} {{ $employee->fname }} {{ $employee->lname }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.employees.updatePermissions', $employee->employee_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="permissions">Select Permissions</label><br>

                @foreach($features as $feature)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="permission_{{ $feature }}" name="permissions[]" value="{{ $feature }}" 
                            @if($permissions->pluck('feature')->contains($feature)) checked @endif>
                        <label class="form-check-label" for="permission_{{ $feature }}">{{ ucfirst($feature) }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Update Permissions</button>
        </form>
    </div>
    </div>
@endsection
