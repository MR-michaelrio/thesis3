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
                                        <option value="{{$d->id_leave}}">{{$d->leave->leave_name}}</option>
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
                                        <input type="text" class="form-control" name="id_employee" value="{{Auth::user()->identification_number}}" placeholder="Enter Employee ID" disabled="">
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
                    console.log(data);
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
        let leaveTime = $('select[name="leave_time"]').val(); // Half Day atau Full Day
        let startDateValue = $('input[name="leave_start_date"]').val();
        let endDateValue = $('input[name="leave_end_date"]').val();

        // Jika tidak ada tanggal mulai atau selesai, set kuota menjadi 0
        if (!startDateValue || !endDateValue) {
            $('input[name="leave_quota_requested"]').val("0");  // Set nilai default (0)
            return;
        }

        // Kirim data tanggal ke server untuk dihitung
        $.ajax({
            url: '{{ route("leave.calculateQuota") }}',  // Ganti dengan rute yang sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                leave_start_date: startDateValue,
                leave_end_date: endDateValue,
                leave_time: leaveTime
            },
            success: function(response) {
                // Set hasil kuota yang dihitung ke field input
                $('input[name="leave_quota_requested"]').val(response.requested_quota);
            },
            error: function(xhr, status, error) {
                alert('Error calculating leave quota.');
            }
        });
    }

    $('select[name="leave_time"], input[name="leave_start_date"], input[name="leave_end_date"]').on('change', function() {
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
