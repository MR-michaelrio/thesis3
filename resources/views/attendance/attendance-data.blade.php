@extends('index')
@section('title','Attendance Data')
@section('css')
<style>
    /* Custom CSS for active tab background */
    .nav-tabs .nav-link.active {
        background-color: #027BFF !important; /* Replace this with your desired 'prinart' color */
        color: white !important; /* Ensure text is readable on the colored background */
        border-color: #027BFF !important; /* Match border with background */
    }
    .nav-tabs .nav-link {
        color: #000; /* Default text color for inactive tabs */
    }
    #table2 table {
        width: 100% !important;
    }
    
    #loadingIndicator {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
        display: flex;
        justify-content: center; /* Horizontal center */
        align-items: center;   /* Vertical center */
        z-index: 9999;
    }

    .spinner {
        width: 60px; /* Spinner size */
        height: 60px;
        border: 5px solid rgba(0, 0, 0, 0.1);
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }


</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Tab Navigation -->
                 @if(Auth::user()->role != "employee")
                    <ul class="nav nav-tabs" id="attendanceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-1" data-toggle="tab" href="#table1" role="tab" aria-controls="table1" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-2" data-toggle="tab" href="#table2" role="tab" aria-controls="table2" aria-selected="false">Summary</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" style="padding:10px 10px 10px 10px; border:1px solid #DDE2E5" id="attendanceTabsContent">
                        <!-- Table 1 -->
                        <div class="tab-pane fade show active" id="table1" role="tabpanel" aria-labelledby="tab-1">
                            <button type="button" class="btn" id="showFilterBtn1">
                                <i class="fas fa-filter"></i> Filter
                            </button>    
                            <div class="col-3">               
                                <form action="{{ route('attendance.data') }}" method="GET" id="filterForm1" style="display: none;" onsubmit="goToOverviewTab(event)">
                                    <div class="form-group">
                                        <label for="daterange1">Date Range:</label>
                                        <input type="text" name="daterange1" id="daterange1" class="form-control" value="{{ request('daterange1') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-3">Filter</button>
                                </form>
                            </div>     
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Department Code</th>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Daily Total</th>
                                        <th>Regular Hours</th>
                                        <th>Overtime</th>
                                        <th>Attendance Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overview as $a)
                                        <tr style="text-transform:capitalize;" data-attendance-id="{{$a->id_attendance}}">
                                            <td>{{$a->employee->full_name}}</td>
                                            <td>{{$a->employee->user->identification_number}}</td>
                                            <td>{{$a->employee->user->department->department_code}}</td>
                                            <td>{{$a->attendance_date}}</td>
                                            <td>{{$a->shift->shift_name}}</td>
                                            <td>{{$a->clock_in}}</td>
                                            <td>{{$a->clock_out}}</td>
                                            <td style="font-weight:bold">{{ $a->daily_total ? \Carbon\Carbon::parse($a->daily_total)->format('H:i') . ' Hours' : '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($a->shift->clock_in)->diff(\Carbon\Carbon::parse($a->shift->clock_out))->format('%H:%I') }} Hours</td>
                                            <td style="color:red">{{$a->total_overtime}}</td>
                                            <td style="font-weight:bold;{{ $a->attendance_status == 'late' ? 'color:red;' : '' }}">
                                                {{$a->attendance_status}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Table 2 -->
                        <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="tab-2">
                            <button type="button" class="btn" id="showFilterBtn2">
                                <i class="fas fa-filter"></i> Filter
                            </button>    
                            <div class="col-3">               
                                <form action="{{ route('attendance.data') }}" method="GET" id="filterForm2" style="display: none;" onsubmit="goToSummaryTab(event)">
                                    <div class="form-group">
                                        <label for="daterange2">Date Range:</label>
                                        <input type="text" name="daterange2" id="daterange2" class="form-control" value="{{ request('daterange2') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-3">Filter</button>
                                </form>
                            </div> 
                            <div class="card">
                                <div class="body">
                                <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Department Code</th>
                                        <th>Daily Total</th>
                                        <th>Leave</th>
                                        <th>Absence</th>
                                        <th>Overtime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summary as $a)
                                        <tr style="text-transform:capitalize;">
                                            <td>{{$a->full_name}}</td>
                                            <td>{{$a->identification_number}}</td>
                                            <td>{{$a->department_code}}</td>
                                            <td style="font-weight:bold">{{ $a->total_daily_total ? $a->total_daily_total . ' Hours' : '-' }}</td>
                                            <td style="font-weight:bold">{{ $a->total_approved_leave_quota ? $a->total_approved_leave_quota : '-' }}</td>
                                            <td style="font-weight:bold">{{ $a->total_absent ? $a->total_absent : '-' }}</td>
                                            <td style="color:red">{{ $a->total_overtime ? $a->total_overtime . ' Hours' : '-' }}</td>                                 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @else
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Department Code</th>
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Daily Total</th>
                                <th>Regular Hours</th>
                                <th>Overtime</th>
                                <th>Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overview as $a)
                            <tr>
                                <td>{{$a->employee->full_name}}</td>
                                <td>{{$a->employee->user->identification_number}}</td>
                                <td>{{$a->employee->user->department->department_code}}</td>
                                <td>{{$a->attendance_date}}</td>
                                <td>{{$a->shift->shift_name}}</td>
                                <td>{{$a->clock_in}}</td>
                                <td>{{$a->clock_out}}</td>
                                <td style="font-weight:bold">
                                    {{ $a->daily_total ? \Carbon\Carbon::parse($a->daily_total)->format('H:i') . ' Hours' : '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->shift->clock_in)->diff(\Carbon\Carbon::parse($a->shift->clock_out))->format('%H:%I') }}
                                    Hours</td>
                                <td style="color:red">{{$a->total_overtime}}</td>
                                <td style="text-transform:capitalize">{{$a->attendance_status}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="loadingIndicator" style="display: none;">
    <div class="spinner"></div>
</div>
<div class="modal fade" id="addmanualmodal" tabindex="-1" role="dialog" aria-labelledby="addManualModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#0FBEF2">
                <h5 class="modal-title" id="addManualModalLabel">Update Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm">
                    @csrf()
                    <div class="form-group">
                        <label for="employeeID">Employee ID</label>
                        <input type="text" class="form-control" id="employeeID" name="employeeID" readonly>
                        <input type="hidden" class="form-control" id="attendanceID" name="attendanceID">
                    </div>
                    <div class="form-group">
                        <label for="employeeName">Employee Name</label>
                        <input type="text" class="form-control" id="employeeName" name="employeeName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="clockIn">Clock In</label>
                        <input type="text" class="form-control" id="clockIn" name="clockIn" placeholder="HH:MM">
                    </div>
                    <div class="form-group">
                        <label for="clockOut">Clock Out</label>
                        <input type="text" class="form-control" id="clockOut" name="clockOut" placeholder="HH:MM">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateButton">Update</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
$(document).ready(function () {
    // Tangkap klik pada baris tabel
    $('#example1 tbody').on('click', 'tr', function () {
        // Ambil data dari baris yang diklik
        const attendanceID = $(this).data('attendance-id');
        const employeeName = $(this).find('td:nth-child(1)').text().trim();
        const employeeID = $(this).find('td:nth-child(2)').text().trim();
        const clockIn = $(this).find('td:nth-child(6)').text().trim();
        const clockOut = $(this).find('td:nth-child(7)').text().trim();

        // Isi data ke dalam form modal
        $('#attendanceID').val(attendanceID);
        $('#employeeID').val(employeeID);
        $('#employeeName').val(employeeName);
        $('#clockIn').val(clockIn);
        $('#clockOut').val(clockOut);

        // Tampilkan modal
        $('#addmanualmodal').modal('show');
    });

    // Tangkap klik tombol update
    $('#updateButton').on('click', function () {
        // Ambil data dari form
        var loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'flex';

        const updatedData = {
            attendanceID: $('#attendanceID').val(),
            employeeID: $('#employeeID').val(),
            employeeName: $('#employeeName').val(),
            clockIn: $('#clockIn').val(),
            clockOut: $('#clockOut').val(),
        };

        // Kirim data ke backend
        $.ajax({
            url: '{{ route("attendance-update") }}', // Endpoint Laravel untuk update
            method: 'POST',
            data: {
                ...updatedData,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                loadingIndicator.style.display = 'none'; // Hide the loading indicator

                showSuccesPopup('Data updated successfully!');
                location.reload(); // Refresh halaman
            },
            error: function (error) {
                loadingIndicator.style.display = 'none'; // Hide the loading indicator

                console.error('Error updating data:', error);
            }
        });

        // Tutup modal
        $('#addmanualmodal').modal('hide');
    });
});


$('#daterange1').daterangepicker({
    locale: {
        format: 'DD/MM/YYYY'
    }
})
$('#daterange2').daterangepicker({
    locale: {
        format: 'DD/MM/YYYY'
    }
})
// When the Filter button is clicked
document.getElementById('showFilterBtn1').addEventListener('click', function() {
    // Show the filter form
    document.getElementById('filterForm1').style.display = 'block';
});

document.getElementById('showFilterBtn2').addEventListener('click', function() {
    // Show the filter form
    document.getElementById('filterForm2').style.display = 'block';
});

function goToSummaryTab(event) {
    event.preventDefault(); // Prevent the default form submission
    document.getElementById('filterForm2').submit(); // Submit the form
}

function goToOverviewTab(event) {
    event.preventDefault(); // Prevent the default form submission
    document.getElementById('filterForm1').submit(); // Submit the form
}

// Automatically switch to the "Summary" tab after page reload (if `daterange` parameter is in the URL)
window.addEventListener('DOMContentLoaded', function() {
    // Check if 'daterange1' or 'daterange2' are present in the URL
    if (window.location.search.indexOf('daterange1') !== -1) {
        // Switch to the "Overview" tab
        $('#tab-1').tab('show');
    } else if (window.location.search.indexOf('daterange2') !== -1) {
        // Switch to the "Summary" tab
        $('#tab-2').tab('show');
    } else {
        // If there are no 'daterange' parameters, make sure the Overview tab is active
        $('#tab-1').tab('show');
    }
});

// After submitting filterForm1, ensure the correct tab is selected
document.getElementById('filterForm1').addEventListener('submit', function(event) {
    // Ensure the form submission occurs only if the "Overview" tab is active
    event.preventDefault(); // Prevent the default form submission
    if ($('#tab-1').hasClass('active')) {
        $('#tab-1').tab('show'); // Ensure tab-1 is active
    }
    this.submit(); // Manually submit the form after handling the tab
});

// After submitting filterForm2, ensure the correct tab is selected
document.getElementById('filterForm2').addEventListener('submit', function(event) {
    // Ensure the form submission occurs only if the "Summary" tab is active
    event.preventDefault(); // Prevent the default form submission
    if ($('#tab-2').hasClass('active')) {
        $('#tab-2').tab('show'); // Ensure tab-2 is active
    }
    this.submit(); // Manually submit the form after handling the tab
});

</script>
<script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
</script>
@endsection
