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
            <!-- <form action="" method="post"> -->
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
                    <a href="{{route('attendance.create')}}" class="btn btn-block btn-sm" style="color:#007bff">Register Face Recognition here!</a>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    let stream = null;
    let isPopupDisplayed = false; // Flag untuk memeriksa apakah popup sedang ditampilkan

    // Access the camera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((mediaStream) => {
                stream = mediaStream;
                video.srcObject = mediaStream;
            })
            .catch((err) => {
                console.error('Error accessing the camera: ', err);
            });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    startCamera();

    // Display popup
    function showPopup(name) {
        isPopupDisplayed = true; // Set flag ketika popup ditampilkan
        const existingOverlay = document.getElementById('popup-overlay');
        const existingPopup = document.getElementById('popup');
        if (existingOverlay) document.body.removeChild(existingOverlay);
        if (existingPopup) document.body.removeChild(existingPopup);
        const overlay = document.createElement('div');
        overlay.id = 'popup-overlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        overlay.style.zIndex = '999';

        const popup = document.createElement('div');
        popup.id = 'popup';
        popup.style.position = 'fixed';
        popup.style.top = '50%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';
        popup.style.padding = '20px';
        popup.style.background = '#fff';
        popup.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.25)';
        popup.style.borderRadius = '10px';
        popup.style.zIndex = '1000';
        popup.innerHTML = `
            <h4>Wajah Terdeteksi</h4>
            <p>${name} telah dikenali!</p>
            <button id="confirmButton" style="margin-top: 10px; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 5px;">Setuju</button>
        `;

        document.body.appendChild(overlay);
        document.body.appendChild(popup);

        document.getElementById('confirmButton').addEventListener('click', () => {
            console.log("Klik setuju",name);
            const id_employe = name;
            const currentDate = new Date();
            const attendanceDate = currentDate.toISOString().split('T')[0]; // YYYY-MM-DD format
            const clockIn = currentDate.toISOString(); // Full timestamp: YYYY-MM-DDTHH:mm:ss.sssZ

            const payload = {
                id_employe: id_employe,
                attendance_date: attendanceDate,  // Send the current date
                clock_in: clockIn       // Send the current timestamp
            };

            axios.post("{{route('attendance.checkin')}}", payload)
                .then(response => {
                    console.log("Hasil Absen :", response);
                    const faceNames = response.data.face_names;
                })
                .catch(error => {
                    console.error('Error Absen:', error.response ? error.response.data : error.message);
                });

            document.body.removeChild(overlay);
            document.body.removeChild(popup);
            isPopupDisplayed = false; // Reset flag setelah popup ditutup
            startCamera(); // Mulai ulang kamera
        }, { once: true });
    }

    // Capture frame every X milliseconds and send to the backend
    setInterval(() => {
        if (isPopupDisplayed) return; // Skip jika popup sedang ditampilkan

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert the canvas image to Blob
        canvas.toBlob((blob) => {
            const formData = new FormData();
            formData.append('image', blob, 'frame.jpg');

            // Send the frame to the backend
            axios.post('/recognize', formData)
                .then(response => {
                    console.log("Hasil:", response.data.message || response.data.face_names);
                    const faceNames = response.data.face_names;
                    
                    if (faceNames.length > 0) {
                        stopCamera(); // Stop the camera
                        showPopup(faceNames[0]); // Show the popup with the first detected name
                    }
                })
                .catch(error => {
                    console.error('Error processing frame:', error.response ? error.response.data : error.message);
                });
        }, 'image/jpeg');
    }, 500); // Adjust interval to control the frame rate
</script>

@endsection
