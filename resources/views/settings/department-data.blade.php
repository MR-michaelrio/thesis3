@extends('index')
@section('title','Department/Division')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#07BEF1; color:white;">
                <div class="row">
                    <div class="col-6 d-flex align-items-center text-white">
                        <h3 class="card-title">Department/Division List</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a href="/department-add" class="btn btn-primary pr-4 pl-4">Add</a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Department/Division Name</th>
                            <th>Code</th>
                            <th>Supervisor</th>
                            <th>Parent Department/Division</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trident</td>
                            <td>Internet Explorer 4.0</td>
                            <td>Win 95+</td>
                            <td> 4</td>
                            <td>X</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection
