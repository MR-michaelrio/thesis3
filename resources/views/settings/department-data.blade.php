@extends('index')
@section('title','Department/Division')
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
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#07BEF1; color:white;">
                <div class="row">
                    <div class="col-6 d-flex align-items-center text-white">
                        <h3 class="card-title">Department/Division List</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a href="{{route('department.create')}}" class="btn btn-primary pr-4 pl-4">Add</a>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                        <tr>
                            <td>{{ $department->department_name }}</td>
                            <td>{{ $department->department_code }}</td>
                            <td>{{ $department->supervisor ? $department->supervisor->full_name : 'N/A' }}</td>
                            <td>{{ $department->parent ? $department->parent->department_name : 'N/A' }}</td>
                            <td>{{ $department->description }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a href="{{ route('department.edit', $department->id_department) }}" class="btn btn-block text-left custom-btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('department.destroy', $department->id_department) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-block text-left custom-btn"
                                                onclick="return confirm('Are you sure you want to delete this data?');">
                                                <i class="fas fa-trash mr-2"></i>Delete Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection
