@extends('index')
@section('title','Employee Data')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#07BEF1; color:white;">
                <div class="row">
                    <div class="col-6 d-flex align-items-center text-white">
                        <h3 class="card-title">Employee Data</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a href="javascript:void(0)" class="pr-4 pl-4" data-toggle="collapse" data-target="#filterForm" aria-expanded="false" aria-controls="filterForm" style="color:white"><i class="fas fa-filter"></i> Filter</a>
                        <a href="/employee-add" class="btn btn-primary pr-4 pl-4 ml-2">Add</a>
                    </div>
                </div>
            </div>
            
            <!-- Filter Form (Initially Collapsed) -->
            <div id="filterForm" class="collapse">
                <div class="card m-4" style="border:1px solid #F7F7F7">
                    <div class="card-header"  style="background-color:#F7F7F7;">
                        <h3 class="card-title">Filters</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="employeeName">Employee Name</label>
                                    <input type="text" class="form-control" id="employeeName" name="employeeName" placeholder="Enter employee name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="department">Department/Division Code</label>
                                    <input type="text" class="form-control" id="department" name="department" placeholder="Enter department code">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="positionTitle">Position Title</label>
                                    <input type="text" class="form-control" id="positionTitle" name="positionTitle" placeholder="Enter position title">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dateOfBirth">Date of Birth</label>
                                    <input type="text" class="form-control" id="dateOfBirth" name="dateOfBirth" placeholder="DD/MM/YYYY - DD/MM/YYYY">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country">Country</label>
                                    <select class="form-control" id="country" name="country">
                                        <option value="">Select Country</option>
                                        <!-- Add country options here -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Apply</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <a href="javascript:void(0)" class="btn btn-danger" data-toggle="collapse" data-target="#filterForm">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.Filter Form -->

            <!-- Data Table -->
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Department/Division</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Email</th>
                            <th>Position Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trident</td>
                            <td>Internet Explorer 4.0</td>
                            <td>Win 95+</td>
                            <td>4</td>
                            <td>X</td>
                            <td>4</td>
                            <td>X</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.Data Table -->
        </div>
    </div>
</div>
@endsection
