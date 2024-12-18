@extends('index')
@section('title','Overtime')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Request Overtime</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{route('overtimes.store')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Start Date</label>
                                <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        placeholder="DD/MM/YYYY" data-target="#reservationdate1" id="reservationdate1" name="overtime_date">
                                    <div class="input-group-append" data-target="#reservationdate1"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input type="text" class="form-control" placeholder="HH:MM - HH:MM" disabled="" id="start" name="start">
                                        <input type="hidden" class="form-control" id="mulai" name="mulai">
                                        <input type="hidden" class="form-control" id="akhir" name="akhir">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Total Overtime</label>
                                        <input type="text" class="form-control" name="total_overtime" id="total_overtime" disabled="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Requester</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" placeholder="Requester ID" value="{{Auth::id()}}" name="id_employee" disabled="">
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="Requester Name" 
                                        value="{{ Auth::user()->employee->full_name }}" 
                                        disabled>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Request Description</label>
                                <textarea class="form-control" rows="3"
                                    placeholder="Enter leave Description" name="request_description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Request Attachment</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="request_file">
                                        <label class="custom-file-label" for="customFile" >Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                    <!-- Change this to type="button" to prevent form submission -->
                    <button type="button" class="btn btn-default float-right mr-3" onclick="window.history.back()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
    $('input[name="overtime_date"]').on('input', function () {
        var selectedDate = $(this).val();
        var formattedDate = moment(selectedDate, 'DD/MM/YYYY').format('Y-M-D');
        console.log(formattedDate);

        $.ajax({
            url: '{{ route("overtime.clock", ":date") }}'.replace(':date', formattedDate),
            method: 'GET',
            success: function (response) {
                    // Populate the fields with the response data
                    $('input[name="start"]').val(response.start);
                    $('input[name="mulai"]').val(response.mulai);
                    $('input[name="akhir"]').val(response.akhir);
                    $('input[name="total_overtime"]').val(response.total_overtime);
            },
            error: function (xhr, status, error) {
                // Display detailed error messages from the backend
                if (xhr.status === 404) {
                    alert(xhr.responseJSON.error + `\nDate: ${xhr.responseJSON.date}`);
                } else if (xhr.status === 500) {
                    alert('Internal Server Error: ' + xhr.responseJSON.error);
                } else {
                    alert('An error occurred while fetching data: ' + error);
                }

                console.error('Error details:', error);
                console.error('Response:', xhr.responseJSON);
            },
        });
    });
});

$(document).ready(function() {
    // Handle custom file input
    $('#customFile').on('change', function(e) {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endsection
