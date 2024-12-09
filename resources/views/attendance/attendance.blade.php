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
    let isPopupDisplayed = false; // Prevent multiple popups
    let captureInterval = null; // Store interval reference

    // Access the camera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((mediaStream) => {
                stream = mediaStream;
                video.srcObject = mediaStream;
            })
            .catch((err) => {
                console.error('Error accessing the camera:', err);
            });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    startCamera();

    function showPopup(name) {
        if (isPopupDisplayed) return; // Prevent multiple popups
        isPopupDisplayed = true; // Set flag
        stopCamera(); // Stop camera
        clearInterval(captureInterval); // Stop capturing frames

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
            console.log("Klik setuju:", name);
            const loadingMessage = document.createElement('div');
            loadingMessage.id = 'loadingMessage';
            loadingMessage.style.position = 'fixed';
            loadingMessage.style.top = '50%';
            loadingMessage.style.left = '50%';
            loadingMessage.style.transform = 'translate(-50%, -50%)';
            loadingMessage.style.padding = '20px';
            loadingMessage.style.background = 'rgba(0, 0, 0, 0.7)';
            loadingMessage.style.color = 'white';
            loadingMessage.style.borderRadius = '10px';
            loadingMessage.style.fontSize = '16px';
            loadingMessage.innerHTML = 'Processing attendance, please wait...';

            document.body.appendChild(loadingMessage);


            // Send attendance data
            const currentDate = new Date();
            const attendanceDate = currentDate.toISOString().split('T')[0]; // YYYY-MM-DD
            const clock = currentDate.toTimeString().split(' ')[0]; // HH:mm:ss

            document.body.removeChild(popup);

            isPopupDisplayed = false; // Reset flag

            axios.post("{{route('attendance.checkin')}}", {
                id_employee: name,
                attendance_date: attendanceDate,
                clock: clock,
            })
                .then(response => {
                    console.log("Hasil Absen:", response.data);
                    alert(response.data.message);

                    document.body.removeChild(loadingMessage);
                    document.body.removeChild(overlay);
                    startCamera(); // Restart camera
                    startFrameCapture(); // Restart frame capture
                })
                .catch(error => {
                    console.error("Error Absen:", error.response ? error.response.data : error.message);
                    alert(error.response ? error.response.data.message : "An error occurred.");
                    document.body.removeChild(overlay);
                    document.body.removeChild(popup);

                    isPopupDisplayed = false; // Reset flag
                    startCamera(); // Restart camera
                    startFrameCapture(); // Restart frame capture
                    document.body.removeChild(loadingMessage);

                });

            
        }, { once: true });
    }

    function startFrameCapture() {
        captureInterval = setInterval(() => {
            if (isPopupDisplayed) return; // Skip if popup is displayed

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob((blob) => {
                const formData = new FormData();
                formData.append('image', blob, 'frame.jpg');

                axios.post('/recognize', formData)
                    .then(response => {
                        console.log("Hasil:", response.data.message || response.data.face_names);
                        const faceNames = response.data.face_names || [];
                        if(faceNames[0] === "Unknown"){
                            console.log("Wajah tidak dikenali, mencoba lagi...");
                            return;
                        }else{
                            if (faceNames.length > 0) {
                                showPopup(faceNames[0]); // Show popup with the first detected name
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error processing frame:', error.response ? error.response.data : error.message);
                    });
            }, 'image/jpeg');
        }, 500);
    }

    startFrameCapture(); // Start capturing frames
</script>
@endsection
