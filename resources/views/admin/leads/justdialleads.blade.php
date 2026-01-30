@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Lead Management</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>

                <h2 class="pt-2">Justdial Leads</h2>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class=" mb-3">
                <h3>Justdial Leads</h3>
                <form method="GET" action="{{ route('admin.leads.justdialleads') }}" class="form-inline mb-4">
                    <lable for="search">Search by Name, Mobile, Email, Company, or City</lable>
                    <input type="text" name="search" class="form-control mx-2 mb-2" placeholder="Search by Name, Mobile, Email, Company, or City" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary mx-2"><i class="fas fa-search"></i> Search</button>
                    <a href="{{ route('admin.leads.justdialleads') }}" class="btn btn-secondary mx-2 ">Reset</a>
                </form>
            </div>

            @if($leads->isEmpty())
                <div class="alert alert-warning p-2">
                    No leads found.
                </div>
            @else
                <div class="table-responsive mb-3">
                    <table class="table table-bordered bg-light">
                        <thead>
                            <tr>
                                 <th>Lead ID</th>
                        <th>Lead Type</th>
                        <th>Prefix</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>City</th>
                        <th>Area</th>
                        <th>Branch Area</th>
                        <th>DNC Mobile</th>
                        <th>DNC Phone</th>
                        <th>Company</th>
                        <th>Pincode</th>
                        <th>Time</th>
                        <th>Branch Pin</th>
                        <th>Parent ID</th>
                        <th>Lead Source</th>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                                <tr>
                                    <td>{{ $lead->leadid }}</td>
                            <td>{{ $lead->leadtype }}</td>
                            <td>{{ $lead->prefix }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->mobile }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->date }}</td>
                            <td>{{ $lead->category }}</td>
                            <td>{{ $lead->city }}</td>
                            <td>{{ $lead->area }}</td>
                            <td>{{ $lead->brancharea }}</td>
                            <td>{{ $lead->dncmobile ? 'Yes' : 'No' }}</td>
                            <td>{{ $lead->dncphone ? 'Yes' : 'No' }}</td>
                            <td>{{ $lead->company }}</td>
                            <td>{{ $lead->pincode }}</td>
                            <td>{{ $lead->time }}</td>
                            <td>{{ $lead->branchpin }}</td>
                            <td>{{ $lead->parentid }}</td>
                            <td>{{ $lead->lead_source }}</td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                
                <div class="pagination-container">
                {{ $leads->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>

            @endif
        </div>
    </div>
</div>
</div>
@endsection
