@extends('index')
@section('title', 'Leave Type')
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
                        <h3 class="card-title">Leave Type List</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-primary pr-4 pl-4" data-toggle="modal" data-target="#modal-default" onclick="openAddModal()">
                            Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Leave Type Name</th>
                            <th>Category</th>
                            <th>Valid Date (From)</th>
                            <th>Valid Date (To)</th>
                            <th>Allocation</th>
                            <th>Default Quota</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                        <tr>
                            <td>{{ $leave->leave_name }}</td>
                            <td>{{ $leave->category }}</td>
                            <td>{{ $leave->valid_date_from }}</td>
                            <td>{{ $leave->valid_date_end }}</td>
                            <td>{{ $leave->allocation }}</td>
                            <td>{{ $leave->default_quota }}</td>
                            <td>{{ $leave->description }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <button type="button" class="btn btn-block text-left custom-btn" onclick="openEditModal({{ $leave }})" data-toggle="modal" data-target="#modal-default">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('leaves.destroy', $leave->id_leave) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-block text-left custom-btn" onclick="return confirm('Are you sure you want to delete this data?');">
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
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#07BEF1; color:white;">
                <h4 class="modal-title" id="modal-title">Leave Type Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="leaveForm" action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="form-method">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Leave Name</label>
                        <input type="text" class="form-control" name="leave_name" id="leave_name" placeholder="Enter leave type name">
                    </div>
                    <div class="form-group">
                        <label>Leave Category</label>
                        <select class="form-control" name="category" id="category">
                            <option disabled>Select a leave category</option>
                            <option value="Annual">Annual</option>
                            <option value="Fixed Duration">Fixed Duration</option>
                            <option value="Non-Annual">Non-Annual</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Leave Default Quota</label>
                        <input type="number" class="form-control" name="default_quota" id="default_quota" placeholder="Enter leave type quota">
                    </div>
                    <div class="form-group">
                        <label>Leave Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter leave type description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Valid Date From</label>
                        <input type="date" class="form-control" name="valid_date_from" id="valid_date_from">
                    </div>
                    <div class="form-group">
                        <label>Valid Date End</label>
                        <input type="date" class="form-control" name="valid_date_end" id="valid_date_end">
                    </div>
                </div>
                <div class="modal-footer" style="background-color:#E6FAFF;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add Leave Type";
        document.getElementById("leaveForm").action = "{{ route('leaves.store') }}";
        document.getElementById("form-method").value = "POST";
        document.getElementById("leave_name").value = "";
        document.getElementById("category").value = "";
        document.getElementById("default_quota").value = "";
        document.getElementById("description").value = "";
        document.getElementById("valid_date_from").value = "";
        document.getElementById("valid_date_end").value = "";
    }

    function openEditModal(leave) {
        document.getElementById("modal-title").innerText = "Edit Leave Type";
        document.getElementById("leaveForm").action = "{{ route('leaves.update', '') }}/" + leave.id_leave;
        document.getElementById("form-method").value = "PUT";
        document.getElementById("leave_name").value = leave.leave_name;
        document.getElementById("category").value = leave.category;
        document.getElementById("default_quota").value = leave.default_quota;
        document.getElementById("description").value = leave.description;
        document.getElementById("valid_date_from").value = leave.valid_date_from || null;
        document.getElementById("valid_date_end").value = leave.valid_date_end || null;
    }
</script>
@endsection
