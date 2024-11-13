@extends('index')
@section('title','Face Recognition')
@section('content')
<div class="row" style="height: 75vh">
    <!-- Set the row height as needed -->
    <div class="col-lg-7" style="border: 1px solid #CED4DA; background-color: #F3F6F9; border-radius: 3px 0 0 3px; padding: 0; height: 100%;">
        <video id="video" style="width: 100%; height: 100%;" autoplay></video>
        <canvas id="canvas" style="display: none; width: 100%; height: 100%;"></canvas>
    </div>
    <div class="col-lg-5" style="border: 1px solid #CED4DA; border-radius: 0 3px 3px 0; padding: 0; height: 100%;">
        <div class="card" style="border-radius: 0; height: 100%;">
            <div class="card-header" style="border-radius: 0; background-color: #0CBEF2; color: white;">
                <h3 class="card-title">Attendance Information</h3>
            </div>
            <form action="" method="post">
                <div class="card-body" style="height: calc(100% - 56px);">
                    <div class="form-group">
                        <label>Attendance</label>
                        <select class="form-control">
                            <option>Clock In</option>
                            <option>Clock Out</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="employeid">Employee ID</label>
                        <input type="text" class="form-control" id="employeid" placeholder="Enter ID">
                    </div>
                    <div class="form-group">
                        <label for="employename">Employee Name</label>
                        <input type="text" class="form-control" id="employename" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="employeemail">Employee Email</label>
                        <input type="email" class="form-control" id="employeemail" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                    <label>Clock</label>
                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime">
                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary btn-sm">Add Manually</button>
                    <a href="" class="btn btn-block btn-sm" style="color:#007bff">Register Face Recognition here!</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const faceNamesList = document.getElementById('face-names-list');
    const detectionsList = document.getElementById('detections');

    // Access the camera
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then((stream) => {
            video.srcObject = stream;
        })
        .catch((err) => {
            console.error('Error accessing the camera: ', err);
        });

    // Capture frame every X milliseconds and send to the backend
    setInterval(() => {
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert the canvas image to Blob
        canvas.toBlob((blob) => {
            const formData = new FormData();
            formData.append('image', blob, 'frame.jpg');

            // Send the frame to the backend
            axios.post('/recognize', formData)
                .then(response => {
                    console.log("hasil", response);
                    const faceNames = response.data.face_names || [];
                    const detections = response.data.detections || [];

                    // Clear previous results
                    faceNamesList.innerHTML = '';
                    detectionsList.innerHTML = '';

                    // Display each recognized face name
                    faceNames.forEach(name => {
                        const listItem = document.createElement('li');
                        listItem.textContent = name;
                        faceNamesList.appendChild(listItem);
                    });

                    // Display each detection
                    detections.forEach(detection => {
                        const listItem = document.createElement('li');
                        listItem.textContent =
                            `Detected: ${detection.name}, Confidence: ${detection.confidence.toFixed(2)}`;
                        detectionsList.appendChild(listItem);
                    });
                })
                .catch(error => {
                    console.error('Error processing frame:', error.response ? error.response.data :
                        error.message);
                });
        }, 'image/jpeg');
    }, 500); // Adjust interval to control the frame rate

</script>
@endsection
