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
                    <table id="AdminAccount" class="table ">
                        <thead>
                            <tr style="display:none">
                                <th>Photo</th>
                                <th>Profile</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admin as $a)
                            <tr>
                                <td>
                                    <div
                                        style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [{{$a->name}}]
                                    </div>
                                    <div class="col text-primary">
                                        {{$a->id_user}} | {{$a->email}}
                                    </div>
                                </td>
                                <td>[{{$a->id_department}}]</td>
                                <td>ad</td>
                            </tr>
                            @endforeach
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
                    <table id="SupervisorAccount" class="table ">
                        <thead>
                            <tr style="display:none">
                                <th>Photo</th>
                                <th>Profile</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supervisor as $s)
                            <tr>
                                <td>
                                    <div
                                        style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [{{$s->name}}]
                                    </div>
                                    <div class="col text-primary">
                                        {{$s->id_user}} | {{$s->email}}
                                    </div>
                                </td>
                                <td>[{{$s->id_department}}]</td>
                                <td>ad</td>
                            </tr>
                            @endforeach
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
                    <table id="EmployeeAccount" class="table ">
                        <thead>
                            <tr style="display:none">
                                <th>Photo</th>
                                <th>Profile</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee as $e)
                            <tr>
                                <td>
                                    <div
                                        style="background-color:#CED4DA; border-radius:50%; width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <i class="far fa-user fa-2x"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        [{{$e->name}}]
                                    </div>
                                    <div class="col text-primary">
                                        {{$e->id_user}} | {{$e->email}}
                                    </div>
                                </td>
                                <td>[{{$e->id_department}}]</td>
                                <td>ad</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
