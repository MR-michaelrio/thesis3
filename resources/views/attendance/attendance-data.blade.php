
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
                    </thead>
                    <tbody>
                        @foreach($attendance as $a)
                            <tr>
                                <td>{{$a->employee->full_name}}</td>
                                <td>{{$a->id_employee}}</td>
                                <td>{{$a->employee->user->id_department}}</td>
                                <td>{{$a->attendance_date}}</td>
                                <td>{{$a->shift->shift_name}}</td>
                                <td>{{$a->clock_in}}</td>
                                <td>{{$a->clock_out}}</td>
                                <td>{{$a->daily_total}}</td>
                                <td>{{$a->shift->clock_in}} - {{$a->shift->clock_out}}</td>
                                <td>{{$a->total_overtime}}</td>
                                <td>{{$a->attendance_status}}</td>
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
