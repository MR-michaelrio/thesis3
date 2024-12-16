
@extends('index')
@section('title','Attendance Data')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
            </div> -->
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Department Code</th>
                            <th>Date</th>
                            <th>Shift</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Daily Total</th>
                            <th>Regular Hours</th>
                            <th>Overtime</th>
                            <th>Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $a)
                            <tr>
                                <td>{{$a->employee->full_name}}</td>
                                <td>{{$a->employee->user->identification_number}}</td>
                                <td>{{$a->employee->user->department->department_code}}</td>
                                <td>{{$a->attendance_date}}</td>
                                <td>{{$a->shift->shift_name}}</td>
                                <td>{{$a->clock_in}}</td>
                                <td>{{$a->clock_out}}</td>
                                <td style="font-weight:bold">{{ $a->daily_total ? \Carbon\Carbon::parse($a->daily_total)->format('H:i') . ' Hours' : '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->shift->clock_in)->diff(\Carbon\Carbon::parse($a->shift->clock_out))->format('%H:%I') }} Hours</td>
                                <td style="color:red">{{$a->total_overtime}}</td>
                                <td style="text-transform:capitalize">{{$a->attendance_status}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Department/Division Code</th>
                            <th>Date</th>
                            <th>Shift</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Daily Total</th>
                            <th>Regular Hours</th>
                            <th>Overtime</th>
                            <th>Attendance Status</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection
