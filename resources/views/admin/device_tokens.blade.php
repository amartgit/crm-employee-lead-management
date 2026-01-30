@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Device Token Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.device.tokens.update') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>User</th>
                        <th>Device Token</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deviceTokens as $token)
                        <tr>
                            <td><input type="checkbox" name="token_ids[]" value="{{ $token->id }}"></td>
                            <td>{{ $token->user->employee_id ?? 'N/A' }} - {{ $token->user->name ?? 'N/A' }} (ID: {{ $token->user_id }})</td>
                            <td>{{ $token->device_token }}</td>
                            <td>
                                @if($token->is_verified)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $token->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-group mt-3">
            <button type="submit" name="action" value="approve" class="btn btn-success">Approve Selected</button>
            <button type="submit" name="action" value="reject" class="btn btn-warning">Reject Selected</button>
            <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete selected tokens?')">Delete Selected</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('selectAll').onclick = function () {
        let checkboxes = document.querySelectorAll('input[name="token_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    };
</script>
@endsection
