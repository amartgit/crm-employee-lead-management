@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Employee Management</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid p-2">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employee List</h4>
            <input type="text" id="employeeSearch" class="form-control w-25" placeholder="Search Employee..." onkeyup="filterEmployees()">
        </div>
        <div class="table-responsive">


            <table class="table table-striped" id="employeeTable">
                <thead>
                    <tr>
                        <th class="fw-bolder text-black">Employee ID </th>

                        <th class="fw-bolder text-black">Name</th>
                        <th class="fw-bolder text-black">Phone</th>
                        <th class="fw-bolder text-black">Email</th>
                        <th class="fw-bolder text-black">Role/Department</th>

                        <th class="fw-bolder text-black">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    
                    <tr>
                        <td>{{ $employee->employee_id }} <span class="{{ in_array($employee->id, $activeSessions) ? 'blinking-online' : '' }}">
                    {{ in_array($employee->id, $activeSessions) ? 'online' : '' }}</td>
                        <td>{{ $employee->fname }} {{ $employee->lname }}</td>
                        <td>{{ $employee->phone_number }}</td>
                        <td>{{ $employee->mailid }}</td>
                        <td>{{ $employee->User->role }} || {{ $employee->department }} ||
                        @if($employee->user->role !== 'SuperAdmin')
                                <a href="{{ route('superadmin.employees.editRoleDep', $employee->employee_id) }}" class="btn btn-primary mx-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role & Department">
                                <i class="fas fa-cogs"></i>
                                </a>
                                @endif</td>

                        <td class="d-flex g-4">
                            <button type="button" class="btn btn-info mx-2" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="View Details"  data-toggle="modal" data-target="#employeeModal" onclick="showEmployeeDetails('{{ $employee->employee_id }}')"><i
                            class="fas fa-eye"></i></button>
                            <a href="{{ route('superadmin.employees.edit', $employee->id) }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit User" class="btn btn-warning mx-2"><i
                            class="fas fa-user-edit"></i></a>
                            
                           

                            <form action="{{ route('superadmin.employees.destroy', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mx-2" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Delete User"><i
                                            class="fas fa-trash-alt"></i></button>
                            </form>
                            
                            
                        </td> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Employee Details -->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Employee Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loading" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="employee-details" aria-live="polite"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter Employees
    function filterEmployees() {
        const input = document.getElementById('employeeSearch').value.toLowerCase();
        const rows = document.querySelectorAll('#employeeTable tbody tr');
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            row.style.display = name.includes(input) ? '' : 'none';
        });
    }

    // Show Employee Details
    function showEmployeeDetails(employeeId) {
        document.getElementById('loading').classList.remove('d-none');
        document.getElementById('employee-details').innerHTML = '';
        fetch(`/superadmin/employees/${employeeId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').classList.add('d-none');
                const employee = data.employee;
                const details = `
                    <p><span class="h6">Full Name : </strong> ${employee.fname} ${employee.mname || ''} ${employee.lname}</p>
                    <p><span class="h6">Phone Number : </span> ${employee.phone_number}</p>
                    <p><span class="h6">WhatsApp Number : </span> ${employee.whatsapp_number || 'N/A'}</p>
                    <p><span class="h6">DOB : </span> ${employee.dob || 'N/A'}</p>
                    <p><span class="h6">Gender : </span> ${employee.gender}</p>
                    <p><span class="h6">Email : </span> ${employee.mailid}</p>
                     <p><strong>Blood Group:</strong> ${employee.blood_group || 'N/A'}</p>
                    <p><strong>Emergency Contact:</strong> ${employee.Emergency_contact || 'N/A'}</p>
                    <p><span class="h6">Address : </span> ${employee.address || 'N/A'}</p>
                `;
                document.getElementById('employee-details').innerHTML = details;
            })
            .catch(error => {
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('employee-details').innerHTML = `<p class="text-danger">Failed to load details. Please try again later.</p>`;
                console.error('Error fetching employee details:', error);
            });
    }

    // Confirm Delete
    function confirmDelete() {
        return confirm('Are you sure you want to delete this employee?');
    }
</script>
@endsection
