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
                        <a href="{{ route('employee.create') }}" class="btn btn-primary pr-4 pl-4 ml-2">Add</a>
                    </div>
                </div>
            </div>

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
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->id_employee }}</td>
                            <td>{{ $employee->user->department->department_name ?? '-' }}</td>
                            <td>{{ $employee->gender }}</td>
                            <td>{{ $employee->date_of_birth }}</td>
                            <td>{{ optional($employee->user)->email ?? '-' }}</td>
                            <td>{{ $employee->user->position->position_title ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- /.Data Table -->
        </div>
    </div>
</div>
@endsection
