
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
                                <th>{{$a->employee->full_name}}</th>
                                <th>{{$a->id_employee}}</th>
                                <th>{{$a->employee->user->id_department}}</th>
                                <th>{{$a->attendance_date}}</th>
                                <th>{{$a->shift->shift_name}}</th>
                                <th>{{$a->clock_in}}</th>
                                <th>{{$a->clock_out}}</th>
                                <th>{{$a->daily_total}}</th>
                                <th>{{$a->shift->clock_in}} - {{$a->shift->clock_out}}</th>
                                <th>{{$a->total_overtime}}</th>
                                <th>{{$a->attendance_status}}</th>
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
