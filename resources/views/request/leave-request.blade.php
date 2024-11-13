
@extends('index')
@section('title','Leave')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Request Leave</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Leave Type</label>
                                <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Half Day / Full Day?</label>
                                <select class="form-control">
                                    <option>Half Day</option>
                                    <option>Full Day</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" placeholder="DD/MM/YYYY" data-target="#reservationdate1">
                                            <div class="input-group-append" data-target="#reservationdate1" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Quota Requested</label>
                                        <input type="text" class="form-control" placeholder="0" disabled="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" placeholder="DD/MM/YYYY" data-target="#reservationdate2">
                                            <div class="input-group-append" data-target="#reservationdate2" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Quota Remaining</label>
                                        <input type="text" class="form-control" placeholder="0" disabled="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Request For</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" placeholder="Enter Employee ID" required>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="Employee Name" disabled="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Request Description</label>
                                <textarea class="form-control" rows="3" placeholder="Enter leave Description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Request Attachment</label>
                                <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
                <button type="submit" class="btn btn-default float-right mr-3">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
