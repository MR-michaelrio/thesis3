@extends('index')
@section('title','Shift')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#07BEF1; color:white;">
                <div class="row">
                    <div class="col-6 d-flex align-items-center text-white">
                        <h3 class="card-title">Shift List</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-primary pr-4 pl-4" data-toggle="modal" data-target="#modal-default">
                            Add
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Shift Name</th>
                            <th>Code</th>
                            <th>Supervisor</th>
                            <th>Parent Department/Division</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trident</td>
                            <td>Internet Explorer 4.0</td>
                            <td>Win 95+</td>
                            <td> 4</td>
                        </tr>
                        <tr>
                            <td>asd</td>
                            <td>Internet Explorer 4.0</td>
                            <td>Win 95+</td>
                            <td> 4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#07BEF1; color:white;">
                <h4 class="modal-title">Shift Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="shiftname">Email address</label>
                    <input type="text" class="form-control" id="shiftname" placeholder="Enter Shift Name">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="clockin">Clock In</label>
                            <input type="text" class="form-control" id="clockin" placeholder="HH:MM">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="clockout">Clock Out</label>
                            <input type="text" class="form-control" id="clockout" placeholder="HH:MM">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Shift Description</label>
                    <textarea class="form-control" rows="3" placeholder="Enter Shift Description"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background-color:#E6FAFF;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
