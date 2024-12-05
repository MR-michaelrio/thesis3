
@extends('index')
@section('title','Overtime Data')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Employee Id</th>
                            <th>Overtime Date</th>
                            <th>Time</th>
                            <th>Total Overtime</th>
                            <th>Status</th>
                            <th>Approver ID</th>
                            <th>Request ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overtimes as $o)
                        <tr>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                            <td>{{ $o->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Employee Id</th>
                            <th>Overtime Date</th>
                            <th>Time</th>
                            <th>Total Overtime</th>
                            <th>Status</th>
                            <th>Approver ID</th>
                            <th>Request ID</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@endsection
