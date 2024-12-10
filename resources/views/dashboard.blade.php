@extends('index')
@section('title','Dashboard')
@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info" style="height:142.52px">
            <div class="inner" style="height:112.24px">
                <h3>{{$RequestLeave}}</h3>

                <p>Leave Request</p>
            </div>
            <div class="icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger" style="height:142.52px">
            <div class="inner" style="height:112.24px">
                <h3>{{$RequestOvertime}}</h3>

                <p>Overtime Request</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning" style="height:142.52px">
            <div class="inner" style="height:112.24px">
                <h3>{{$Employee}}</h3>

                <p>Total Employees</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success" style="height:142.52px">
            <div class="inner" style="height: 112.24px; display: flex; align-items: center; justify-content: center;">
                <img src="assets/img/mdi-face-recognition.png" alt="" srcset="" style="width: 45%; height: auto; max-height: 100%; max-width: 45%;">
            </div>
            <a href="#" class="small-box-footer">Attendance</a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- solid sales graph -->
        <div class="card bg-gradient-info">
    <div class="card-header border-0">
        <h3 class="card-title">
            <i class="fas fa-th mr-1"></i>
            Attendance Graph
        </h3>

        <div class="card-tools">
            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <canvas class="chart" id="attendance-chart"
            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>
    <div class="card-footer bg-transparent">
        <div class="row">
            <div class="col-4 text-center">
                <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                    data-fgColor="#39CCCC">

                <div class="text-white">Present</div>
            </div>
            <div class="col-4 text-center">
                <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                    data-fgColor="#39CCCC">

                <div class="text-white">Absent</div>
            </div>
            <div class="col-4 text-center">
                <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                    data-fgColor="#39CCCC">

                <div class="text-white">On Leave</div>
            </div>
        </div>
    </div>
</div>
        <!-- /.card -->
         
        <!-- TO DO List -->
        <!-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ion ion-clipboard mr-1"></i>
                    To Do List
                </h3>

                <div class="card-tools">
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo1" id="todoCheck1">
                            <label for="todoCheck1"></label>
                        </div>
                        <span class="text">Design a nice theme</span>
                        <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo2" id="todoCheck2" checked>
                            <label for="todoCheck2"></label>
                        </div>
                        <span class="text">Make the theme responsive</span>
                        <small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo3" id="todoCheck3">
                            <label for="todoCheck3"></label>
                        </div>
                        <span class="text">Let theme shine like a star</span>
                        <small class="badge badge-warning"><i class="far fa-clock"></i> 1 day</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo4" id="todoCheck4">
                            <label for="todoCheck4"></label>
                        </div>
                        <span class="text">Let theme shine like a star</span>
                        <small class="badge badge-success"><i class="far fa-clock"></i> 3 days</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo5" id="todoCheck5">
                            <label for="todoCheck5"></label>
                        </div>
                        <span class="text">Check your messages and notifications</span>
                        <small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" value="" name="todo6" id="todoCheck6">
                            <label for="todoCheck6"></label>
                        </div>
                        <span class="text">Let theme shine like a star</span>
                        <small class="badge badge-secondary"><i class="far fa-clock"></i> 1 month</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash-o"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer clearfix">
                <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button>
            </div>
        </div> -->
        <!-- /.card -->
    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">

        <!-- Calendar -->
        <div class="card bg-gradient-success">
            <div class="card-header border-0">

                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                    <!-- button with a dropdown -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                            data-offset="-52">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a href="#" class="dropdown-item">Add new event</a>
                            <a href="#" class="dropdown-item">Clear events</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">View calendar</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- right col -->
</div>
<!-- /.row (main row) -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $('#calendar').datetimepicker({
    format: 'L',
    inline: true
  })
    // Data for the line chart
    var attendanceData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],  // Example months
        datasets: [
            {
                label: 'Present',  // Label for the "Present" line
                data: [20, 30, 40, 60, 50, 70],  // Example data for Present
                fill: false,  // Do not fill the area under the line
                borderColor: 'rgba(0, 123, 255, 1)',  // Line color for Present
                tension: 0.1  // Line smoothness
            },
            {
                label: 'Absent',  // Label for the "Absent" line
                data: [30, 40, 50, 30, 40, 50],  // Example data for Absent
                fill: false,  // Do not fill the area under the line
                borderColor: 'rgba(220, 53, 69, 1)',  // Line color for Absent
                tension: 0.1  // Line smoothness
            },
            {
                label: 'On Leave',  // Label for the "On Leave" line
                data: [10, 20, 30, 10, 20, 10],  // Example data for On Leave
                fill: false,  // Do not fill the area under the line
                borderColor: 'rgba(255, 193, 7, 1)',  // Line color for On Leave
                tension: 0.1  // Line smoothness
            }
        ]
    };

    // Chart.js configuration
    var ctx = document.getElementById('attendance-chart').getContext('2d');
    var attendanceChart = new Chart(ctx, {
        type: 'line',  // Line chart type
        data: attendanceData,
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true  // Start the x-axis at zero
                },
                y: {
                    beginAtZero: true  // Start the y-axis at zero
                }
            },
            plugins: {
                legend: {
                    position: 'top',  // Position the legend at the top
                },
                tooltip: {
                    enabled: true  // Enable tooltips for each point on the line
                }
            }
        }
    });
</script>
@endsection