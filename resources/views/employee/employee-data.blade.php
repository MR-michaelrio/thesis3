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
                            @if(Auth::user()->role == "admin")<th>Status</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->full_name }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->user->identification_number }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->user->department->department_name ?? '-' }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->gender }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->date_of_birth }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ optional($employee->user)->email ?? '-' }}</td>
                            <td @if(Auth::user()->role == "admin") onclick="window.location.href='{{ route('employee.edit', $employee->id_employee) }}'" style="cursor: pointer;" @endif>{{ $employee->user->position->position_title ?? '-' }}</td>
                            @if(Auth::user()->role == "admin")
                            <td>
                                <form action="{{ route('employee.statusupdate', $employee->id_employee) }}" method="post" id="statusForm{{ $employee->id_employee }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" name="status" id="customSwitch{{ $employee->id_employee }}" onclick="toggleStatus({{ $employee->id_employee }})"
                                            {{ $employee->status == 'active' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch{{ $employee->id_employee }}"></label>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            @endif
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
@section('scripts')
<script>
    function toggleStatus(employeeId) {
        // Get the checkbox state
        var isChecked = document.getElementById('customSwitch' + employeeId).checked;

        // Determine the status based on the checkbox state
        var status = isChecked ? 'active' : 'inactive';

        // Prepare the data to send via AJAX
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('status', status);

        // Perform AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ url("employee/statusupdate/") }}/' + employeeId, true);
        xhr.onreadystatechange = function() {
            var response = JSON.parse(xhr.response);

            if (xhr.readyState === 4 && xhr.status === 200) {
                showSuccesPopup(response.message)
                console.log(response.message);
                // Optionally, show a success message or update the UI
            } else if (xhr.readyState === 4) {
                showSuccesPopup(response.message)
                // Optionally, handle the error
            }
        };
        xhr.send(formData);
    }

</script>
@endsection