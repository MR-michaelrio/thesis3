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
                        <button type="button" class="btn btn-primary pr-4 pl-4" data-toggle="modal" data-target="#modal-default" onclick="openAddModal()">
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
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shifts as $shift)
                            <tr>
                                <td>{{$shift->shift_name}}</td>
                                <td>{{$shift->clock_in}}</td>
                                <td>{{$shift->clock_out}}</td>
                                <td>{{$shift->shift_description}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <button type="button" class="btn btn-block text-left" onclick="openEditModal({{ $shift }})" data-toggle="modal" data-target="#modal-default">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('shift.destroy', $shift->id_shift) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block text-left" onclick="return confirm('Are you sure you want to delete this data?');">
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
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#07BEF1; color:white;">
                <h4 class="modal-title" id="modal-title">Shift Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="leaveForm" action="{{ route('shift.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="form-method">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="shiftname">Shift Name</label>
                        <input type="text" class="form-control" id="shift_name" name="shift_name" placeholder="Enter Shift Name">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="clockin">Clock In</label>
                                <input type="time" class="form-control" id="clock_in" name="clock_in" placeholder="HH:MM">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="clockout">Clock Out</label>
                                <input type="time" class="form-control" id="clock_out" name="clock_out" placeholder="HH:MM">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Shift Description</label>
                        <textarea class="form-control" rows="3" name="shift_description" id="shift_description" placeholder="Enter Shift Description"></textarea>
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
        document.getElementById("modal-title").innerText = "Add Shift";
        document.getElementById("leaveForm").action = "{{ route('shift.store') }}";
        document.getElementById("form-method").value = "POST";
        document.getElementById("shift_name").value = "";
        document.getElementById("clock_in").value = "";
        document.getElementById("clock_out").value = "";
        document.getElementById("shift_description").value = "";
    }

    function openEditModal(shift) {
        document.getElementById("modal-title").innerText = "Edit Shift";
        document.getElementById("leaveForm").action = "{{ route('shift.update', '') }}/" + shift.id_shift;
        document.getElementById("form-method").value = "PUT";
        document.getElementById("shift_name").value = shift.shift_name;
        document.getElementById("clock_in").value = shift.clock_in;
        document.getElementById("clock_out").value = shift.clock_out;
        document.getElementById("shift_description").value = shift.shift_description;
    }
</script>
@endsection
