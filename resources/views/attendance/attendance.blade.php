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
                        <input type="text" class="form-control" id="time" placeholder="" disabled>
                        <!-- <select class="form-control">
                            <option>Clock In</option>
                            <option>Clock Out</option>
                        </select> -->
                    </div>
                    <div class="form-group">
                        <label for="employeid">Employee ID</label>
                        <input type="text" class="form-control" id="employeid" placeholder="Enter ID" disabled>
                    </div>
                    <div class="form-group">
                        <label for="employename">Employee Name</label>
                        <input type="text" class="form-control" id="employename" placeholder="Enter Name" disabled>
                    </div>
                    <div class="form-group">
                        <label>Clock</label>
                        <!-- <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime">
                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div> -->
                        <input type="text" class="form-control" id="clock" placeholder="Enter Time" disabled>
                    </div>
                    <button type="button" class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#addmanualmodal">Add Manually</button>
                    <a href="{{route('attendance.create')}}" class="btn btn-block btn-sm" style="color:#007bff">Register Face Recognition here!</a>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
<!-- Modal for Manual Attendance Detail -->
<div class="modal fade" id="addmanualmodal" tabindex="-1" role="dialog" aria-labelledby="addmanualmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#0FBEF2;color:white">
                <h5 class="modal-title" id="addmanualmodal">Manual Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addmanualform" style="text-transform: capitalize;" method="post">
                @csrf
                <div class="modal-body">
                    <div class="col-12">
                        <strong>Employee ID:</strong></span>
                    </div>
                    <div class="col-12">
                        <input type="text" id="id_identification" name="id_identification" class="form-control" placeholder="ID Employee">
                    </div>
                    <div id="loadingSpinner" class="text-center mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Please wait...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('addmanualform').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        const idIdentification = document.getElementById('id_identification').value;
        console.log("idEmployee",idIdentification);
        // Ensure the employee ID is not empty
        if (!idIdentification) {
            alert('Employee ID cannot be empty');
            return;
        }
        document.getElementById('loadingSpinner').style.display = 'block';

        // Prepare the form data to be sent
        const formData = new FormData();
        formData.append('id_identification', idIdentification);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        // Send the request via Fetch API
        fetch('{{ route("attendance-manual") }}', {
            method: 'POST',
            body: formData, // Send the FormData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Server returned an error.');
                });
            }
            return response.json();
        }) // Parse the JSON response
        .then(data => {
            // Handle the response
            console.log('Response data:', data.message);
            document.getElementById('loadingSpinner').style.display = 'none';
            $('#addmanualmodal').modal('hide');

            showSuccesPopup(data.message);

            document.getElementById('employeid').value = data?.employee_id ? data.employee_id : "";
            document.getElementById('employename').value = data?.employee_name ? data.employee_name : "";
            document.getElementById('clock').value = data?.attendance?.clock_in ? data.attendance.clock_in : (data?.attendance?.clock_out || "");
            document.getElementById('time').value = data?.time ? data.time : "";


        })
        .catch(error => {
            // Handle error
            document.getElementById('loadingSpinner').style.display = 'none';

            let errorMessage = 'An error occurred.';
            if (error.message) {
                errorMessage = error.message; // Ambil pesan dari error
            }

            console.error('Error:', errorMessage);
            alert(errorMessage); // Tampilkan pesan error
            document.getElementById('id_identification').value = "";
            startCamera();
            startFrameCapture();
        });
    });

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

    function showPopup(name,faceid) {
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
        // overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        overlay.style.zIndex = '3';

        const popup = document.createElement('div');
        popup.style.position = 'fixed';
        popup.style.top = '50%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';
        popup.style.padding = '30px 70px';
        popup.style.background = '#fff';
        popup.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.25)';
        popup.style.borderRadius = '10px';
        popup.style.textAlign = 'center';
        popup.style.zIndex = '4';
        popup.innerHTML = `
            <div style="margin-bottom: 15px;">
                <svg width="100" height="100" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#34C759" d="M6.75 10.25L4.5 8l-.75.75 3 3 6-6-.75-.75-5.25 5.25z"/>
                </svg>
            </div>
            <h2 style="color: #333; margin: 0 0 10px;">Absensi Berhasil</h2>
            <p style="color: #A1A1A1; margin: 0;font-size:20px;font-transform:capitalize">${name}</p>
            <button id="cancelButton" 
                style="
                    margin-top: 10px; 
                    padding: 10px 20px; 
                    background: #FF3B30; 
                    color: white; 
                    border: none; 
                    border-radius: 5px; 
                    font-size: 18px; 
                    cursor: pointer;
                    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                ">
                Cancel
            </button>
            <button id="confirmButton" 
                style="
                    margin-top: 20px; 
                    padding: 10px 20px; 
                    background: #007bff; 
                    color: white; 
                    border: none; 
                    border-radius: 5px; 
                    font-size: 18px; 
                    cursor: pointer;
                    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                ">
                Continue
            </button>
            
        `;

        document.body.appendChild(overlay);
        document.body.appendChild(popup);

        document.getElementById('confirmButton').addEventListener('click', () => {
            // Send attendance data
            const currentDate = new Date();
            const attendanceDate = currentDate.toISOString().split('T')[0]; // YYYY-MM-DD
            const clock = currentDate.toTimeString().split(' ')[0]; // HH:mm:ss

            document.body.removeChild(popup);
            document.body.removeChild(overlay);

            isPopupDisplayed = false; // Reset flag

                axios.post("{{route('attendance.checkin')}}", {
                    id_employee: faceid,
                    attendance_date: attendanceDate,
                    clock: clock,
                })
                .then(response => {
                    console.log("checkin", response);
                    showSuccesPopup(response.data?.message || response.message);
                    document.getElementById('employeid').value = response.data?.employee_id || "";
                    document.getElementById('employename').value = response.data?.employee_name || "";
                    document.getElementById('clock').value = response.data?.attendance.clock_in || response.data?.attendance.clock_out || "";
                    document.getElementById('time').value = response.data?.time || "";
                    document.body.removeChild(overlay);

                    startCamera(); // Restart camera
                    startFrameCapture(); // Restart frame capture
                })
                .catch(error => {
                    // console.error("Error Absen:", error.response ? error.response.data : error.message);
                    document.body.removeChild(overlay);
                    document.body.removeChild(popup);
                    isPopupDisplayed = false; // Reset flag

                    startCamera(); // Restart camera
                    startFrameCapture(); // Restart frame capture
                });

            
        }, { once: true });

        document.getElementById('cancelButton').addEventListener('click', () => {
            document.body.removeChild(overlay);
            document.body.removeChild(popup);
            isPopupDisplayed = false; // Reset flag

            startCamera(); // Restart camera
            startFrameCapture(); // Restart frame capture
        });
    }

    function startFrameCapture() {
        captureInterval = setInterval(() => {
            if (isPopupDisplayed) return; // Skip if popup is displayed

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob((blob) => {
                const formData = new FormData();
                formData.append('image', blob, 'frame.jpg');
                axios.post('{{route("recognize")}}', formData)
                    .then(response => {
                        console.log("Hasil:", response.data);
                        const faceid = response.data.face_names;
                        const faceConfidence = response.data.detections[0].confidence || [];
                        if(faceid[0] === "Unknown"){
                            return;
                        }else{
                            if (faceid.length > 0) {
                                showPopup(response.data.employees[0].full_name,faceid[0]); // Show popup with the first detected name
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error processing frame:', error.response ? error.response.data : error.message);
                    });
            }, 'image/jpeg');
        }, 500);
    }

    document.querySelector('[data-toggle="modal"][data-target="#addmanualmodal"]').addEventListener('click', () => {
        stopCamera(); // Stop camera when the modal is shown
    });

    $('#addmanualmodal').on('show.bs.modal', function () {
        // Hentikan frame capture saat modal terbuka
        clearInterval(captureInterval);
        stopCamera();
    });

    $('#addmanualmodal').on('hidden.bs.modal', function () {
        // Hidupkan kamera dan frame capture kembali saat modal ditutup
        startCamera();
        startFrameCapture();
    });

    startFrameCapture(); // Start capturing frames
</script>
@endsection
