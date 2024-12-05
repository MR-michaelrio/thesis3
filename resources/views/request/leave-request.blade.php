@extends('index')
@section('title','Leave')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Request Leave</h3>
            </div>
            <form action="{{route('requestleave.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- /.card-header -->
            <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Leave Type</label>
                                <select class="form-control select2 select2-hidden-accessible" name="leave_type" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option disabled selected>Select Leave Type</option>
                                    @foreach($leave as $d)
                                        <option value="{{$d->id_leave}}">{{$d->leave_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" name="leave_start_date" placeholder="DD/MM/YYYY hh:mm" data-target="#reservationdatetime">
                                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group date" id="reservationdatetime1" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" name="leave_end_date" placeholder="DD/MM/YYYY hh:mm" data-target="#reservationdatetime1">
                                            <div class="input-group-append" data-target="#reservationdatetime1" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Half Day / Full Day?</label>
                                <select class="form-control" name="leave_time">
                                    <option disabled selected>Select Option</option>
                                    <option value="half">Half Day</option>
                                    <option value="full">Full Day</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Leave Quota Requested</label>
                                        <input type="text" class="form-control" name="leave_quota_requested" placeholder="0" disabled>
                                        <input type="hidden" class="form-control" name="leave_quota_requested" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Leave Quota Remaining</label>
                                        <input type="text" class="form-control" id="remaining-quota" placeholder="0" disabled>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Request For</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="id_employee" value="{{Auth::user()->employee->id_employee}}" placeholder="Enter Employee ID" disabled="">
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="{{Auth::user()->employee->full_name}}" placeholder="Employee Name" disabled="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Request Description</label>
                                <textarea class="form-control" rows="3" name="request_description" placeholder="Enter leave Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="customFile">Request Attachment</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="request_file">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
                <button type="submit" class="btn btn-default float-right mr-3">Cancel</button>
            </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#customFile').on('change', function(e) {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // Function to fetch remaining quota based on leave type
        function fetchRemainingQuota(leaveType) {
            $.ajax({
                url: '{{ route("leave.remainingQuota") }}',  // Example API URL
                method: 'GET',
                data: { leave_type: leaveType },  // Send selected leave type
                success: function(data) {
                    // Set the remaining quota to the input field
                    $('#remaining-quota').val(data.remaining_quota);  // Update remaining quota field
                }
            });
        }

        // Update remaining quota when leave type changes
        $('select[name="leave_type"]').on('change', function() {
            let leaveType = $(this).val(); // Get selected leave type
            fetchRemainingQuota(leaveType);  // Fetch remaining quota for the selected leave type
        });

        // Fetch remaining quota based on leave type when page loads
        let initialLeaveType = $('select[name="leave_type"]').val();
        if (initialLeaveType) {
            fetchRemainingQuota(initialLeaveType);
        }

        // Function to calculate requested quota based on leave time and dates
      // Function to calculate requested quota based on leave time and dates
      function calculateLeaveQuota() {
            let leaveTime = $('select[name="leave_time"]').val(); // Half Day or Full Day
            let startDateValue = $('input[name="leave_start_date"]').val();
            let endDateValue = $('input[name="leave_end_date"]').val();

            // If no start and end date, set leave quota as 0 or N/A
            if (!startDateValue || !endDateValue) {
                $('input[name="leave_quota_requested"]').val("0");  // Set default value (0)
                return;
            }

            // Parse dates using moment.js
            let startDate = moment(startDateValue, 'DD/MM/YYYY HH:mm', true); // 'true' for strict parsing
            let endDate = moment(endDateValue, 'DD/MM/YYYY HH:mm', true); // 'true' for strict parsing

            if (!startDate.isValid() || !endDate.isValid()) {
                alert("Please enter valid start and end dates.");
                return;
            }

            // Calculate the difference in days
            let daysDifference = endDate.diff(startDate, 'days') + 1; // Include start day

            let requestedQuota = (leaveTime === 'full') ? daysDifference : daysDifference * 0.5; // If full day, use full days, else half days

            // Update the input field with the calculated quota
            $('input[name="leave_quota_requested"]').val(requestedQuota);  
        }

        // Event listener for when the user selects the Leave Time (Half or Full)
        $('select[name="leave_time"]').on('change', function() {
            // When the leave time changes, set the leave quota requested to 0 initially
            $('input[name="leave_quota_requested"]').val("0");

            // Also, recalculate quota if start and end dates are already filled
            let startDateValue = $('input[name="leave_start_date"]').val();
            let endDateValue = $('input[name="leave_end_date"]').val();
            
            if (startDateValue && endDateValue) {
                calculateLeaveQuota();
            }
        });

        // Event listener for when the user changes the Start Date or End Date
        $('input[name="leave_start_date"], input[name="leave_end_date"]').on('change', function() {
            // Recalculate the leave quota when dates are filled in
            calculateLeaveQuota();
        });

        // Initial calculation when page loads if leave time is already selected
        let initialLeaveTime = $('select[name="leave_time"]').val();
        if (initialLeaveTime) {
            $('input[name="leave_quota_requested"]').val("0"); // Set quota to 0 initially
        }

    });
</script>
@endsection
