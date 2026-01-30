@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title mb-3">
                <h2>Leads</h2>
                <p>Welcome, {{ Auth::user()->name }}!</p>

                <h2 class="pt-2">Import Leads From Sheet</h2>
            </div>
        </div>
    </div>
    <div class="container mt-5">
                        <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary"> View Leads</a>

        <div class="card shadow-lg p-1">
            <div class="card-header bg-primary text-white">
                <h4 class="text-white">Import Leads</h4>
                
            </div>
            
            <div class="card-body">
                
                <form id="uploadForm" enctype="multipart/form-data">
                    <div id="dropZone" class="border border-primary rounded p-5 text-center mb-3"
                        style="background-color: #f8f9fa; cursor: pointer;">
                        <h5 class="text-muted" id="dropZoneText">Drag and drop your Excel file here or click to select</h5>
                    </div>
                    <input type="file" id="fileInput" name="file" style="display: none;">
                    <div id="fileInfo" class="text-success mb-3" style="display: none;"></div>
                    <div id="errorMessage" class="text-danger mb-3" style="display: none;"></div>
                    <button type="submit" class="btn btn-success" id="uploadButton">
                        <span id="uploadText">Upload Leads</span>
                        <div id="spinner" class="spinner-border spinner-border-sm text-light ms-2"
                            style="display: none;" role="status"></div>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="table-responsive mb-1 p-2">
                        <h5>*follow Sheet Structure while Import new leads*</h5>

    <!-- Table where imported leads will be displayed -->
    <table border="1" class="table table-bordered bg-light">
        <thead>
            <tr>
                <th>Sr</th>
                <th>Date</th>
                <th>Name</th>
                <th>Company</th>
                <th>Contact Info</th>
                <th>City</th>
                <th>Lead Source</th>
            </tr>
        </thead>
        <tbody id="leadsTableBody"> <!-- Updated ID here to target the table body -->
            <!-- Leads will be dynamically inserted here -->
        </tbody>
    </table>
</div>

    
    </div>
    


    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');
        const fileInfo = document.getElementById('fileInfo');
        const errorMessage = document.getElementById('errorMessage');
        const uploadButton = document.getElementById('uploadButton');
        const uploadText = document.getElementById('uploadText');
        const spinner = document.getElementById('spinner');
        const dropZoneText = document.getElementById('dropZoneText');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('bg-light', 'border-success');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-light', 'border-success');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-light', 'border-success');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                showFileInfo(files[0].name);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                showFileInfo(fileInput.files[0].name);
            }
        });

        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (fileInput.files.length == 0) return alert("Please select a file!");
            const formData = new FormData(uploadForm);
            showLoading(true);

            try {
                const response = await fetch('{{ route("admin.leads.import") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    const result = await response.json();
                    showError(result.error || 'Error while importing leads!');
                    return;
                }
                const result = await response.json();
                alert(result.message || 'Leads imported successfully!');
                displayImportedLeads(result.leads);  // **Added: Call to display imported leads**

                resetForm();
            } catch (error) {
                console.error(error);
                showError('An error occurred while uploading.');
            } finally {
                showLoading(false);
            }
        });

        function resetForm() {
            fileInput.value = '';
            fileInfo.style.display = 'none';
            fileInfo.innerHTML = '';
            dropZoneText.innerHTML = 'Drag and drop your Excel file here or click to select';
            errorMessage.style.display = 'none';
            uploadForm.reset();
        }

        function showFileInfo(fileName) {
            fileInfo.style.display = 'block';
            fileInfo.innerHTML = `<strong>${fileName}</strong> selected.`;
            dropZoneText.innerHTML = `File ready for upload: <strong>${fileName}</strong>`;
        }

        function showLoading(isLoading) {
            if (isLoading) {
                uploadText.innerText = 'Uploading...';
                spinner.style.display = 'inline-block';
                uploadButton.disabled = true;
            } else {
                uploadText.innerText = 'Upload Leads';
                spinner.style.display = 'none';
                uploadButton.disabled = false;
            }
        }

        function showError(message) {
            errorMessage.style.display = 'block';
            errorMessage.innerHTML = `<strong>Error:</strong> ${message}`;
        }
        
        
        
        // **Updated Code: Function to display imported leads**
    function displayImportedLeads(leads) {
        const tableBody = document.getElementById('leadsTableBody');
        tableBody.innerHTML = ''; // Clear previous content

        leads.forEach((lead, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${lead.created_at ? new Date(lead.created_at).toLocaleDateString() : 'N/A'}</td>
                <td>${lead.name}</td>
                <td>${lead.company}</td>
                <td>${lead.contact_info}</td>
                <td>${lead.city}</td>
                <td>${lead.lead_source}</td>
            `;
            tableBody.appendChild(row);
        });
    }
    </script>
</div>
@endsection
