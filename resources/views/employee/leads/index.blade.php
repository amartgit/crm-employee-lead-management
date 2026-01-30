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


<!-- Search Form -->
    <div class="row mb-3">
        <div class="col-md-9">
        <form action="{{ route('employee.leads.index') }}" method="GET" class="mb-3">
            <label  for="search">Search by Name, id, Company, Contact, City, Status or Source</label >

            <div class="d-flex g-2">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, id, Company, Contact, City, Status or Source" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary mx-2">Search</button>
                <a href="{{ route('employee.leads.index') }}" class="btn btn-secondary mx-2">Reset</a>

            </div>
        </form>

        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('employee.leads.my_activity') }}" class="btn btn-primary"> Lead history</a>
            

        </div>
    </div>
    
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        
        <div id="leads-container" class="table-responsive">
            @include('employee.leads.partials.lead-row')
        </div>

  
        
        <div class="pagination-container p-2">
    {{ $leads->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
</div>

    </div>
</div>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function submitActionForm(leadId) {
    const form = document.querySelector(`form[data-lead-id='${leadId}']`);
    const formData = new FormData(form);
    const url = form.getAttribute('action');
    const statusMessage = form.querySelector('.action-status-message');
            statusMessage.innerHTML = `<span class="text-warning">Please Wait ...</span>`;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusMessage.innerHTML = `<span class="text-success">${data.message}</span>`;
        } else {
            statusMessage.innerHTML = `<span class="text-danger">${data.message}</span>`;
        }
        setTimeout(() => {
            statusMessage.innerHTML = '';
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        statusMessage.innerHTML = '<span class="text-danger">Failed to update actions.</span>';
        setTimeout(() => {
            statusMessage.innerHTML = '';
        }, 5000);
    });
}
</script>

<script>
function updateStatus(leadId) {
    const form = document.getElementById('statusForm_' + leadId);
    const formData = new FormData(form);
    const url = form.getAttribute('action');

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msgDiv = document.getElementById('statusMessage_' + leadId);
        if (data.success) {
            msgDiv.innerHTML = '<span class="text-success">' + data.message + '</span>';
        } else {
            msgDiv.innerHTML = '<span class="text-danger">' + data.message + '</span>';
        }
        setTimeout(() => msgDiv.innerHTML = '', 3000);
    })
    .catch(() => {
        const msgDiv = document.getElementById('statusMessage_' + leadId);
        msgDiv.innerHTML = '<span class="text-danger">Status update failed.</span>';
        setTimeout(() => msgDiv.innerHTML = '', 3000);
    });
}
</script>

<script>
    // Initialize Bootstrap tooltips if they are not initialized
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function toggleOnCall(id) {
        const button = $('#callButton_' + id); // Get the button element
        const messageContainer = $('#callStatusMessage_' + id); // Get the message container
        button.prop('disabled', true); // Disable the button while the request is in progress
        messageContainer.html(''); // Clear any previous messages

        $.ajax({
            url: '{{ route("leads.toggleOnCall", ":id") }}'.replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token
            },
            success: function(response) {
                if (response.success) {
                    if (response.on_call) {
                        button.removeClass('btn-success').addClass('btn-danger');
                        button.html('<i class="fa fa-phone-slash"></i> On Call');
                        button.attr('title', 'Click to end the call');  // Update the title for tooltip
                        messageContainer.html('<span class="text-success">Call started successfully!</span>');
                    } else {
                        button.removeClass('btn-danger').addClass('btn-success');
                        button.html('<i class="fa fa-phone"></i> Start Call');
                        button.attr('title', 'Click to start the call');  // Update the title for tooltip
                        messageContainer.html('<span class="text-danger">Call ended successfully!</span>');
                    }

                    // Hide the message after 5 seconds
                    setTimeout(function() {
                        messageContainer.html('');
                    }, 3000);

                    // Re-initialize the tooltip to update the title dynamically
                    button.tooltip('dispose').tooltip();
                } else {
                    messageContainer.html('<span class="text-danger">' + response.message + '</span>');
                    // Hide the "already on call" message after a short delay
                    setTimeout(function() {
                        messageContainer.html('');
                    }, 3000);
                }
                button.prop('disabled', false); // Re-enable the button after the request completes
            },
            error: function(xhr, status, error) {
                messageContainer.html('<span class="text-danger">An error occurred while processing the request. Please try again later.</span>');
                button.prop('disabled', false); // Re-enable the button in case of error
            }
        });
    }
</script>



 <script>
    @if(session('status'))
        var statusMessage = '{{ session('status')['message'] }}';
        var messageType = '{{ session('status')['type'] }}';
        var leadId = '{{ session('status')['id'] }}';

        var messageContainer = $('#statusMessage_' + leadId);
        messageContainer.html('<span class="text-' + (messageType === 'success' ? 'success' : 'danger') + '">' + statusMessage + '</span>');

        setTimeout(function() {
            messageContainer.html('');
        }, 3000);
    @endif
</script>

<script>
   function updateLeads() {
       const urlParams = new URLSearchParams(window.location.search);
        const search = urlParams.get('search') || '';
        const page = urlParams.get('page') || 1;

       const fetchUrl = `{{ route("leads.fetch") }}?search=${search}&page=${page}`;
        
//          const sortField = urlParams.get('sort_field') || 'id';  // Default to sorting by 'id'
//      const sortDirection = urlParams.get('sort_direction') || 'desc';  // Default to ascending order

//      const fetchUrl = `{{ route("leads.fetch") }}?search=${search}&page=${page}&sort_field=${sortField}&sort_direction=${sortDirection}`;


       fetch(fetchUrl)
            .then(response => response.text())
             .then(html => {
                 document.getElementById('leads-container').innerHTML = html;
                console.log("Leads updated on page", page);
             })
             .catch(error => console.error('Error:', error));
    }


     function isTabActive() {
     return document.visibilityState === 'visible';
 }

setInterval(() => {
     if (isTabActive()) {
         updateLeads();
     }
 }, 30000); 

</script> 



@endpush
