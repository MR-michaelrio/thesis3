@extends('index')
@section('title','Face Recognition Registration')
@section('css')
<style>
    #loadingIndicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;  /* Increase the spinner size */
            height: 60px; /* Increase the spinner size */
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .file-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #ddd;
    }
    .file-icon {
        width: 40px;
        height: 40px;
        background-color: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 1.5rem;
    }
    .file-name {
        flex-grow: 1;
    }
    .file-progress {
        width: 100%;
    }
    .file-delete {
        color: red;
        cursor: pointer;
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <section class="col-lg-6 ">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color:#0FBEF2;color:white">
                    <h3 class="card-title">Employee</h3>
                </div>
                <div class="card-body">
                    <table id="AdminAccount" class="table">
                        <thead>
                            <tr style="display:none">
                                <th>Rendering engine</th>
                                <th>Browser</th>
                                <th>Platform(s)</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facelist as $a)
                            <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        {{$a->employee->full_name}}
                                    </div>
                                    <div class="col text-primary">
                                        {{$a->employee->user->identification_number}} | {{$a->employee->user->email}}
                                    </div>
                                </td>
                                <td>{{$a->employee->user->department_code}}</td>
                                <td>
                                    <form action="{{ route('attendance.destroy', $a->id_face_security) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this face?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6 ">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color:#0FBEF2;color:white">
                    <h3 class="card-title">Face Recognition Registration</h3>
                </div>
                <form action="{{route('attendance.store')}}" method="post" id="employeeForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="employeeID">Employee ID</label>
                                <select class="form-control select2" required name="id_employee" id="employeeID" onchange="handleEmployeeChange()">
                                    <option disabled selected>Employee ID</option>
                                    @foreach($employee as $e)
                                        <option value="{{$e->id_employee}}" data-name="{{$e->full_name}}">
                                            {{$e->full_name}}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-message" style="color: red; display: none;">Employee ID is required.</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="container mt-3">
                                <h5>Face</h5>
                                <div class="border rounded p-3 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label class="d-flex align-items-center" for="file-upload">
                                            <i class="fas fa-upload"></i>
                                            <span class="pl-2">Upload photo here</span>
                                        </label>
                                        <small class="text-muted d-block">Accepted file types: JPEG, JPG</small>
                                        <input type="file" class="d-none" name="image" id="file-upload" required accept="image/jpeg, image/jpg" onchange="handleFileUpload(event)" disabled>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary ml-3" id="upload-btn" onclick="triggerFileInput()" disabled>Upload</button>
                                </div>
                            </div>
                            <div class="container mt-3">
                                <h5 id="upload-info">No photo uploaded</h5>
                                <div id="file-list"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" style="float:right" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<div id="loadingIndicator" style="display: none;">
    <div class="spinner"></div>
</div>
@endsection

@section('scripts')
<script>
    function handleEmployeeChange() {
    const employeeSelect = document.getElementById('employeeID');
    const fileUploadInput = document.getElementById('file-upload');
    const uploadButton = document.getElementById('upload-btn');

    if (employeeSelect.value && employeeSelect.value !== "Employee ID") {
        fileUploadInput.disabled = false;
        uploadButton.disabled = false;
    } else {
        fileUploadInput.disabled = true;
        uploadButton.disabled = true;
    }
}

    const files = [];

    function triggerFileInput() {
        document.getElementById('file-upload').click();
    }

    function handleFileUpload(event) {
        const selectedFiles = event.target.files;

        if (selectedFiles.length > 1) {
            alert('You can only upload one photo.');
            event.target.value = ''; // Reset file input
            return;
        }

        const file = selectedFiles[0];
        files.length = 0; // Clear any existing files
        files.push(file);

        document.getElementById('upload-info').textContent = `1 photo uploaded: ${file.name}`;
        updateFileList(file);
    }

    function updateFileList(file) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = `
            <div class="file-item">
                <div class="file-icon"><i class="fas fa-image"></i></div>
                <div class="file-name">${file.name}</div>
                <button type="button" class="btn btn-danger btn-sm ml-3" onclick="removeFile()">Remove</button>
            </div>
        `;
    }

    function removeFile() {
        files.length = 0; // Clear files
        document.getElementById('file-list').innerHTML = '';
        document.getElementById('upload-info').textContent = 'No photo uploaded';
        document.getElementById('file-upload').value = ''; // Reset input
    }

    document.getElementById('employeeForm').addEventListener('submit', function (event) {
        const loadingIndicator = document.getElementById('loadingIndicator');

        event.preventDefault(); // Prevent default form submission to simulate loading
        loadingIndicator.style.display = 'flex';
        // Simulate an action (e.g., form submission or some other async task)
        setTimeout(() => {
            // Hide loading spinner after the task is complete
            loadingIndicator.style.display =  'hide';
            // Optionally, submit the form after the process completes
            event.target.submit();
        }, 3000); // Simulate a 3-second process (replace with real action)
    });
</script>
@endsection
