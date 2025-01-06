@extends('index')
@section('title', 'Attendance Policy')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Attendance Policy</h3>
            </div>
            <form action="{{ route('attendance_policy.updateOrCreate') }}" method="POST">
                @csrf
                <!-- Form Body -->
                <div class="card-body">
                    <!-- Hidden Field for ID -->
                    <input type="hidden" name="id_attendance_policy" value="{{ $policy->id_attendance_policy }}">

                    <div class="form-group">
                        <label>Late Tolerance (after clock in minutes)</label>
                        <input type="number" name="late_tolerance" class="form-control"
                            value="{{ old('late_tolerance', $policy->late_tolerance) }}"
                            placeholder="Enter late tolerance in minutes">
                    </div>

                    <div class="form-group">
                        <label>Overtime Start Time (after clock out)</label>
                        <input type="number" name="overtime_start" class="form-control"
                            value="{{ old('overtime_start', $policy->overtime_start) }}"
                            placeholder="Enter overtime start time in minutes">
                    </div>

                    <div class="form-group">
                        <label>Overtime Maximum End Time (after overtime start time)</label>
                        <input type="number" name="overtime_end" class="form-control"
                            value="{{ old('overtime_end', $policy->overtime_end) }}"
                            placeholder="Enter overtime maximum end time">
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="card-footer" style="background-color:#E7F9FE">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                    <a href="{{ route('attendance_policy.index') }}" class="btn btn-default float-right mr-3">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
