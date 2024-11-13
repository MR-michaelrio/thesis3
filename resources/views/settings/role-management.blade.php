
@extends('index')
@section('title','Account Role Management')
@section('content')
<div class="row">
<section class="col-lg-6 connectedSortable">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Admin Account</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr style="display:none">
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [Full Name]
                                    </div>
                                    <div class="col text-primary">
                                        ID | email.com
                                    </div>
                                </td>
                                <td>[Department Code]</td>
                                <td>ad</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Supervisor Account</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="SupervisorAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr style="display:none">
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [Full Name]
                                    </div>
                                    <div class="col text-primary">
                                        ID | email.com
                                    </div>
                                </td>
                                <td>[Department Code]</td>
                                <td>ad</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<section class="col-lg-6 connectedSortable">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Employee Account</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="EmployeeAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr style="display:none">
                            <th>Rendering engine</th>
                            <th>Browser</th>
                            <th>Platform(s)</th>
                            <th>Engine version</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                                <td>
                                    <div style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [Full Name]
                                    </div>
                                    <div class="col text-primary">
                                        ID | email.com
                                    </div>
                                </td>
                                <td>[Department Code]</td>
                                <td>ad</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
