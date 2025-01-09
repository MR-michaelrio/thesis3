@extends('index')
@section('title','Account Role Management')
@section('css')
<style>
    /* Normal button styles */
    .custom-btn {
        background-color: #007bff; /* Default button color */
        border-radius:0px !important;
    }

    /* Hover effect */
    .custom-btn:hover {
        background-color: #DEE2E8; /* Change background on hover */
        border-color: #DEE2E8; /* Optional: change border color on hover */
    }
</style>
@endsection
@section('content')
<div class="row">
    <section class="col-lg-6">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color:#0FBEF2;color:white">
                    <h3 class="card-title">Admin Account</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="AdminAccount" class="table">
                        <thead>
                            <tr style="display:none">
                                <th>a</th>
                                <th>b</th>
                                <th>c</th>
                                <th>d</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admin as $a)
                            <tr>
                                <td>
                                    @if ($a->employee->profile_picture)
                                        <img 
                                            src="{{ asset('profile_picture/' . $a->employee->profile_picture) }}" 
                                            class="img-circle" 
                                            style="width:50px; height:50px; object-fit: cover;" 
                                            alt="User Image"
                                            onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <!-- Icon sebagai fallback jika gambar gagal dimuat -->
                                        <div style="background-color:#CED4DA; border-radius:50%; width:50px; height:50px; display: none; justify-content: center; align-items: center;">
                                            <i class="far fa-user fa-2x"></i>
                                        </div>
                                    @else
                                        <div style="background-color:#CED4DA; border-radius:50%; width:50px; height:50px; display: flex; justify-content: center; align-items: center;">
                                            <i class="far fa-user fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="col">
                                        {{$a->employee->full_name}}
                                    </div>
                                    <div class="col text-primary">
                                        {{$a->identification_number}} | {{$a->email}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        {{$a->department?->department_code}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <div class="dropdown">
                                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                @if(Auth::user()->company->pic != $a->id_user)
                                                <form action="{{ route('role.pic', $a->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">As PIC</button>
                                                </form>
                                                @endif
                                                <form action="{{ route('role.supervisor', $a->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Move to Supervisor</button>
                                                </form>

                                                <form action="{{ route('role.employee', $a->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Move to Employee</button>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </td>
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
                                <th>a</th>
                                <th>b</th>
                                <th>c</th>
                                <th>d</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supervisor as $s)
                            <tr>
                                <td>
                                    @if ($s->employee->profile_picture)
                                        <img src="{{ asset('profile_picture/' . $s->employee->profile_picture) }}" class="img-circle" style="width:50px; height:50px; object-fit: cover;" alt="User Image">
                                    @else
                                        <div style="background-color:#CED4DA; border-radius:50%; width:50px; height:50px; display: flex; justify-content: center; align-items: center;">
                                            <i class="far fa-user fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="col">
                                        {{$s->employee->full_name}}
                                    </div>
                                    <div class="col text-primary">
                                        {{$s->identification_number}} | {{$s->email}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        {{$s->department?->department_code}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <div class="dropdown">
                                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <form action="{{ route('role.admin', $s->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Make To Admin</button>
                                                </form>

                                                <form action="{{ route('role.employee', $s->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Move to Employee</button>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="col-lg-6">
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
                                <th>a</th>
                                <th>b</th>
                                <th>c</th>
                                <th>d</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee as $e)
                            <tr>
                                <td>
                                    @if ($e->employee->profile_picture)
                                        <img src="{{ asset('profile_picture/' . $e->employee->profile_picture) }}" class="img-circle" style="width:50px; height:50px; object-fit: cover;" alt="User Image">
                                    @else
                                        <div style="background-color:#CED4DA; border-radius:50%; width:50px; height:50px; display: flex; justify-content: center; align-items: center;">
                                            <i class="far fa-user fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="col">
                                        {{$e->employee->full_name}}
                                    </div>
                                    <div class="col text-primary">
                                        {{$e->identification_number}} | {{$e->email}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        {{$e->department?->department_code}}
                                    </div>
                                </td>
                                <td>
                                    <div style="width:50px;height:50px; display: flex; justify-content: center; align-items: center;">
                                        <div class="dropdown">
                                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <form action="{{ route('role.admin', $e->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Make To Admin</button>
                                                </form>

                                                <form action="{{ route('role.supervisor', $e->id_user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-block text-left custom-btn">Move to Supervisor</button>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </td>
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
