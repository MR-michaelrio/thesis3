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
                                    <label for="daterange">Date Range:</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control" value="{{ request('daterange') }}">
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
                                    <tr style="text-transform:capitalize;">
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
                            <form action="{{ route('attendance.data') }}" method="GET" id="filterFor2" style="display: none;" onsubmit="goToSummaryTab(event)">
                                <div class="form-group">
                                    <label for="daterange">Date Range:</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control" value="{{ request('daterange') }}">
                                </div>
                                <button type="submit" class="btn btn-primary mb-3">Filter</button>
                            </form>
                        </div> 
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ID</th>
                                    <th>Department Code</th>
                                    <th>Daily Total</th>
                                    <th>Overtime</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary as $a)
                                    <tr style="text-transform:capitalize;">
                                        <td>{{$a->full_name}}</td>
                                        <td>{{$a->identification_number}}</td>
                                        <td>{{$a->department_code}}</td>
                                        <td style="font-weight:bold">{{ $a->total_daily_total ? $a->total_daily_total . ' Hours' : '' }}</td>
                                        <td style="color:red">{{ $a->total_overtime ? $a->total_overtime . ' Hours' : '' }}</td>                                 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endsection
@section('scripts')
<script>
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
    // Check if 'daterange' is present in the URL
    if (window.location.search.indexOf('daterange') !== -1) {
        // Switch to the "Summary" tab
        $('#tab-2').tab('show');
    } else {
        // If there's no 'daterange', make sure the Overview tab is active
        $('#tab-1').tab('show');
    }
});

// After submitting filterForm1, ensure the correct tab is selected
document.getElementById('filterForm1').addEventListener('submit', function(event) {
    // Ensure the form submission occurs only if the "Overview" tab is active
    if ($('#tab-1').hasClass('active')) {
        $('#tab-1').tab('show'); // Ensure tab-1 is active
    }
});

// After submitting filterForm2, ensure the correct tab is selected
document.getElementById('filterForm2').addEventListener('submit', function(event) {
    // Ensure the form submission occurs only if the "Summary" tab is active
    if ($('#tab-2').hasClass('active')) {
        $('#tab-2').tab('show'); // Ensure tab-2 is active
    }
});
</script>
<script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
</script>
@endsection
