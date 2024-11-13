@extends('index')
@section('title','Face Recognition Registration')
@section('content')

<style>
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

<div class="row">
    <section class="col-lg-6 connectedSortable">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [Full Name]
                                    </div>
                                    <div class="col text-primary">
                                        ID | email.com
                                    </div>
                                </td>
                                <td>[Department Code]</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [michael]
                                    </div>
                                    <div class="col text-primary">
                                        ID | email.com
                                    </div>
                                </td>
                                <td>[Department Code]</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6 connectedSortable">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color:#0FBEF2;color:white">
                    <h3 class="card-title">Face Recognition Registration</h3>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="employeeID">Employee ID</label>
                            <input type="text" class="form-control" id="employeeID" placeholder="[Employee ID]">
                        </div>
                        <div class="form-group">
                            <label for="employeeName">Employee Name</label>
                            <input type="text" class="form-control" id="employeeName" placeholder="[Employee Name]">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="container mt-3">
                            <h5>Face</h5>
                            <div class="border rounded p-3 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <label class="d-flex align-items-center" for="file-upload">
                                        <i class="fas fa-upload"></i>
                                        <span class="pl-2">Upload files here</span>
                                    </label>
                                    <small class="text-muted d-block">Accepted file types: JPEG, JPG</small>
                                    <input type="file" class="d-none" id="file-upload" accept="image/jpeg, image/jpg, image/png" multiple onchange="handleFileUpload(event)">
                                </div>
                                <button type="button" class="btn btn-outline-primary ml-3" onclick="triggerFileInput()">Upload</button>
                            </div>
                        </div>
                        <div class="container mt-3">
                            <h5><span id="upload-count">0</span> of <span id="total-count">0</span> files uploaded</h5>
                            <div id="file-list">
                                <!-- File items will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function triggerFileInput() {
        document.getElementById('file-upload').click();
    }

    // Function to handle file upload
    function handleFileUpload(event) {
        const files = event.target.files;
        console.log(files); // Here you can handle the uploaded files, like preview or upload to server
    }

    const files = [];

    function handleFileUpload(event) {
        const selectedFiles = Array.from(event.target.files);
        selectedFiles.forEach(file => {
            files.push({ name: file.name, progress: 0, status: 'pending' });
        });
        document.getElementById('total-count').textContent = files.length;
        updateFileList();
    }

    function updateFileList() {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';

        let uploadedCount = 0;

        files.forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';

            // Icon
            const fileIcon = document.createElement('div');
            fileIcon.className = 'file-icon';
            fileIcon.innerHTML = '<i class="fas fa-image"></i>';
            fileItem.appendChild(fileIcon);

            // File name and progress
            const fileInfo = document.createElement('div');
            fileInfo.className = 'file-name';

            const fileName = document.createElement('div');
            fileName.textContent = file.name;
            fileInfo.appendChild(fileName);

            const progressContainer = document.createElement('div');
            progressContainer.className = 'progress file-progress';

            const progressBar = document.createElement('div');
            progressBar.className = 'progress-bar';
            progressBar.style.width = `${file.progress}%`;
            progressBar.textContent = `${file.progress}%`;

            if (file.progress === 100) {
                progressBar.classList.add('bg-success');
                uploadedCount++;
            } else {
                progressBar.classList.add('bg-primary');
            }
            progressContainer.appendChild(progressBar);
            fileInfo.appendChild(progressContainer);

            fileItem.appendChild(fileInfo);

            // Delete icon
            const deleteIcon = document.createElement('i');
            deleteIcon.className = 'bi bi-trash file-delete';
            deleteIcon.onclick = () => removeFile(file.name);
            deleteIcon.innerHTML = '<i class="fas fa-trash-alt"></i>';
            fileItem.appendChild(deleteIcon);

            fileList.appendChild(fileItem);
        });

        document.getElementById('upload-count').textContent = uploadedCount;
    }

    function removeFile(fileName) {
        const index = files.findIndex(file => file.name === fileName);
        if (index !== -1) {
            files.splice(index, 1);
            document.getElementById('total-count').textContent = files.length;
            updateFileList();
        }
    }

    // Simulate upload progress
    function simulateUpload() {
        files.forEach((file, index) => {
            if (file.progress < 100) {
                file.progress += 10;
                setTimeout(simulateUpload, 500);
            }
        });
        updateFileList();
    }

    // Start the simulation when files are added
    document.getElementById('file-upload').addEventListener('change', () => {
        simulateUpload();
    });

</script>
@endsection
