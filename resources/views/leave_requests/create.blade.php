@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-4">
                <h2 class="fw-bold">Raise Leave Request</h2>
            </div>
        <div class="container">
            <a href="{{ route('leave.index') }}" class="btn btn-secondary m-2">My Leave Requests</a>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            {{-- Success Message --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- General Error Message --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('leave.store') }}">
                                @csrf

                                {{-- Start Date --}}
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- End Date --}}
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Leave Type --}}
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type" id="leave_type" class="form-select" required>
                                        <option value="" disabled {{ old('leave_type') ? '' : 'selected' }}>Select Leave Type</option>
                                        <option value="Casual" {{ old('leave_type') == 'Casual' ? 'selected' : '' }}>Casual</option>
                                        <option value="Sick" {{ old('leave_type') == 'Sick' ? 'selected' : '' }}>Sick</option>
                                        <option value="Paid" {{ old('leave_type') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Unpaid" {{ old('leave_type') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    </select>
                                    @error('leave_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Reason --}}
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason</label>
                                    <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Optional...">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Submit Button --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

{{-- Optional JavaScript for client-side date validation --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        endDate.addEventListener('change', function () {
            if (startDate.value && endDate.value < startDate.value) {
                alert('End date cannot be earlier than start date.');
                endDate.value = '';
            }
        });
    });
</script>
@endpush

@endsection
