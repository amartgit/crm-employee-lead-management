<!-- resources/views/admin/attendance/edit.blade.php -->

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Edit Attendance Record</h2>


            </div>
        </div>
    </div>

<div class="container">

    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="total_working_time">Total Working Time (hours)</label>
            <input type="number" name="total_working_time" class="form-control" value="{{ $attendance->total_working_time }}" required>
        </div>

        <div class="form-group">
            <label for="total_break_time">Total Break Time (hours)</label>
            <input type="number" name="total_break_time" class="form-control" value="{{ $attendance->total_break_time }}" required>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ $attendance->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Attendance</button>
    </form>
</div>
</div>
@endsection
