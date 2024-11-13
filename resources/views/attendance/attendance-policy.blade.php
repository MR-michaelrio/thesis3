
@extends('index')
@section('title','Attendance Policy')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Attendance Policy</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Shift</label>
                                <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Clock In</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" class="form-control" placeholder="HH:MM">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Clock Out</label>
                                        <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                            <input type="text" class="form-control" placeholder="HH:MM">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Late Tolerance (after clock in minutes)</label>
                                <input type="text" class="form-control" placeholder="Enter number of minutes">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Overtime Start Time (after clout out)</label>
                                <input type="text" class="form-control" placeholder="Enter number of minutes">
                            </div>
                            <div class="form-group">
                                <label>Overtime Maximum End Time (after overtime start time)</label>
                                <input type="text" class="form-control" placeholder="Enter number of minutes">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

            <div class="card-footer" style="background-color:#E7F9FE">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
                <button type="submit" class="btn btn-default float-right mr-3">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
