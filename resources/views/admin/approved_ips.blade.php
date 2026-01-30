@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Manage Approved IPs</h2>

                {{-- FLASH MESSAGES --}}
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    <strong>Access Denied:</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container p-2">
        <h3>Manage Approved IPs</h3>

        <div class="p-2 shadow-lg">
            <!--<form id="addIpForm" class="mb-4">-->
            <!--    @csrf-->
            <!--    <div class="row g-2">-->
            <!--        <div class="col-md-4">-->
            <!--            <input name="ip_address" class="form-control" placeholder="New IP" required>-->
            <!--        </div>-->
            <!--        <div class="col-md-4">-->
            <!--            <input name="label" class="form-control" placeholder="Label (optional)">-->
            <!--        </div>-->
            <!--        <div class="col-md-4">-->
            <!--            <button class="btn btn-primary">Add IP</button>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</form>-->
            <form id="addIpForm" class="mb-4">
                @csrf
                <div id="ipMsgBox"></div> <!-- Message box -->
                <div class="row g-2">
                    <div class="col-md-4">
                        <lable for="ip_address">New IP(s), comma/space/newline separated</lable>
                        <input name="ip_address" class="form-control"
                            placeholder="New IP(s), comma/space/newline separated" required>
                    </div>
                    <div class="col-md-4">
                        <lable for="ip_address">Label / Catogary</lable>

                        <input name="label" class="form-control" required placeholder="Label">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary">Add IP(s)</button>
                    </div>
                </div>
            </form>

        </div>
<hr>
        <div class="p-2">
            <form id="bulkUpdateForm">
                @csrf
                <button class="btn btn-success">Bulk Update Approvals</button>

                <div class="table-responsive my-2">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>IP</th>
                                <th>Label</th>
                                <th>Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ips as $index => $ip)
                            <tr>
                                <td>{{ $ips->firstItem() + $index }}</td>
                                <td>{{ $ip->ip_address }}</td>
                                <td>{{ $ip->label }}</td>
                                <td>
                                    <input type="checkbox" name="ips[{{ $ip->id }}]" value="1" {{ $ip->approved ?
                                    'checked' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $ips->links('pagination::bootstrap-5') }}
                </div>
        </div>
        </form>
    </div>


</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function showMessage(type, message) {
        const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show mt-2" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
        $('.page_title').append(alertHtml);
    }


    $(document).ready(function () {
        // $('#addIpForm').on('submit', function (e) {
        //     e.preventDefault();
        //     $.post("{{ route('admin.ips.store') }}", $(this).serialize())
        //         .done(function (data) {
        //             showMessage('success', 'IP added successfully!');
        //             setTimeout(() => location.reload(), 1000); // reload with delay
        //         })
        //         .fail(function (xhr) {
        //             const msg = xhr.responseJSON?.message || 'Failed to add IP.';
        //             showMessage('danger', msg);
        //             console.error(xhr.responseJSON);
        //         });
        // });

        $('#addIpForm').on('submit', function (e) {
            e.preventDefault();
            $.post("{{ route('admin.ips.store') }}", $(this).serialize())
                .done(function (data) {
                    showMessage('success', data.message || 'IP(s) added successfully!');
                    setTimeout(() => location.reload(), 5000);
                })
                .fail(function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to add IP(s).';
                    showMessage('danger', msg);
                    console.error(xhr.responseJSON);
                });
        });

        $('#bulkUpdateForm').on('submit', function (e) {
            e.preventDefault();
            $.post("{{ route('admin.ips.bulk-update') }}", $(this).serialize())
                .done(function (data) {
                    showMessage('success', data.message);
                    setTimeout(() => location.reload(), 4000);
                })
                .fail(function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Failed to update IP approvals.';
                    showMessage('danger', msg);
                    console.error(xhr.responseJSON);
                });
        });
    });
</script>
@endpush


@endsection