@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2 class="display-6">Leads Management</h2>
                <p class="text-muted">Welcome, <strong>{{ Auth::user()->name }}</strong>!</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="bg-dark text-light">
                    <tr>
                         <th>ID</th> 
                        <th>Name</th>
                        <th>Contact Info</th>
                        <th>Priority</th>
                        <th>UploadDate</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody id="leads-table-body">
                    @forelse ($leads as $lead)
                    <tr id="lead-row-{{ $lead->id }}">
                         <td>{{ $lead->id }}</td> 
                        <td class="lead-name">{{ $lead->name }}</td>
                        <td class="lead-contact">{{ $lead->contact_info }}</td>

                        <td class="lead-priority">{{ ucfirst($lead->priority) }}</td>

                        <td class="lead-date">{{ $lead->upload_date }}</td>
                                <td>
                                <!-- Button to redirect with the Lead ID as filter -->
                                <a href="{{ route('employee.leads.index', ['search' => $lead->id]) }}" class="btn btn-primary"> <i class="fas fa-eye"></i></a>
                            </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No leads available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

         <div class="pagination-container p-2 d-flex justify-content-center">
    {{ $leads->appends(request()->all())->links('pagination::bootstrap-5') }}
</div>
    </div>
</div>

@endsection
