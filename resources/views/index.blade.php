<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AntTendance</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/logo/logo.png')}}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
    <!-- Iconfy -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/main.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{asset('assets/plugins/bs-stepper/css/bs-stepper.min.css')}}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{asset('assets/plugins/dropzone/min/dropzone.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('assets/logo/logo.png')}}" alt="AdminLTELogo"
                height="200" width="200">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color:#0998C1;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
                            style="color:white"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{route('home')}}" class="nav-link" style="color:white; text-transform: capitalize;">{{Auth::user()->role}} Panel | SIMULASI</a>
                </li>
            </ul>

            <!-- Right navbar links
            <ul class="navbar-nav ml-auto">
                Notifications Dropdown Menu
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
            </ul> -->
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{route('home')}}" class="brand-link" style="background-color:#0998C1;">
                <img src="{{asset('img/' . Auth::user()->company->logo)}}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-bold" style="color:white">{{Auth::user()->company->company_name}}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex items-center space-x-3 " onclick="window.location.href='{{ route('employee.edit', Auth::user()->employee->id_employee) }}'" style="cursor: pointer;color:white">
                    <div class="row justify-content-center align-items-center">
                        <div class="col ">
                            @if(Auth::user()->employee->profile_picture)
                                <img src="{{ asset('profile_picture/' . Auth::user()->employee->profile_picture) }}" class="img-circle" style="width:40px; height:40px; object-fit: cover;" alt="User Image">
                            @else
                                <div style="background-color:#CED4DA; border-radius:50%; width:40px; height:40px; display: flex; justify-content: center; align-items: center;">
                                    <i class="far fa-user fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <div class="text-base text-capitalize">{{ Auth::user()->employee->full_name }}</div>
                            <div class="text-sm text-capitalize">{{ Auth::user()->role }}</div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    @if(Auth::user()->role != "superadmin")
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('calendar') }}" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Calendar
                                </p>
                            </a>
                        </li>
                        @if(Auth::user()->role == "supervisor" || Auth::user()->role == "admin")
                            <li class="nav-item">
                                <a href="{{ route('employee.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Employee Data
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->role == "supervisor" || Auth::user()->role == "employee")
                            <li class="nav-item">
                                <a href="{{route('attendance.data')}}" class="nav-link">
                                    <i class="nav-icon fas fa-user-clock"></i>
                                    <p>
                                    Attendance Data
                                    </p>
                                </a>
                            </li>
                        @else 
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user-clock"></i>
                                    <p>
                                        Attendance
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('attendance.index')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Take Attendance</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('attendance.data')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Attendance Data</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-thumbs-up"></i>
                                <p>
                                    Approval
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('requestleave.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Leave Approval</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('overtimes.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Overtime Approval</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Requests
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('requestleave.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Leave</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('overtimes.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Overtime</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if(Auth::user()->role == "admin")
                            <li class="nav-item">
                                <a href="{{ route('invoice.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                    <p>
                                        Invoices
                                    </p>
                                </a>
                            </li>
                        
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Settings
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('role.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Role Management</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('companies.index')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Company Profile</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('department.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Department/Division</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('shift.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Shift</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('leaves.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Leave Type</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('attendance.create')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Face Registration</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('attendance_policy.index')}}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Attendance Policy</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link" id="logoutButton">
                                <i class="nav-icon fas fa-sign-out-alt"></i> Sign Out
                            </a>
                        </li>
                    </ul>
                    @else
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('clientindex') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i> 
                                <p>
                                    Client
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('client.invoiceindex') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i> 
                                <p>
                                    Invoices
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link" id="logoutButton">
                                <i class="nav-icon fas fa-sign-out-alt"></i> 
                                <p>
                                    Sign Out
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endif
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="#">AntTendance</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.1
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)

    </script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <!-- daterangepicker -->
    <script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- Summernote -->
    <script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('assets/dist/js/adminlte.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('assets/dist/js/demo.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/fullcalendar/main.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <!-- BS-Stepper -->
    <script src="{{asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
    <!-- dropzonejs -->
    <script src="{{asset('assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <!-- moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    @yield("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Periksa apakah ada pesan success dari session
            @if(session('success'))
                showSuccesPopup("{{ session('success') }}");
            @endif
        });
    </script>
    <!-- popup success -->
    <script>
        const overlay = document.getElementById('popup-overlay');
        if (overlay) {
            document.body.removeChild(overlay);
        } else {
            console.error('Overlay tidak ditemukan di DOM.');
        }
        function showSuccesPopup(message) {
            const overlay = document.createElement('div');
            overlay.id = 'popup-overlay2';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.zIndex = '3';

            const popup = document.createElement('div');
            popup.style.width = "400px";
            popup.style.position = 'fixed';
            popup.style.top = '50%';
            popup.style.left = '50%';
            popup.style.transform = 'translate(-50%, -50%)';
            popup.style.padding = '30px 70px';
            popup.style.background = '#fff';
            popup.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.25)';
            popup.style.borderRadius = '10px';
            popup.style.textAlign = 'center';
            popup.style.zIndex = '4';
            popup.innerHTML = `
                <div style="margin-bottom: 15px;">
                    <svg width="100" height="100" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#34C759" d="M6.75 10.25L4.5 8l-.75.75 3 3 6-6-.75-.75-5.25 5.25z"/>
                    </svg>
                </div>
                <h2 style="color: #333; margin: 0 0 10px;">${message}</h2>
                <button id="closeButton" 
                    style="
                        margin-top: 20px; 
                        padding: 10px 20px; 
                        background: #FF3B30; 
                        color: white; 
                        border: none; 
                        border-radius: 5px; 
                        font-size: 18px; 
                        cursor: pointer;
                        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                    ">
                    Close
                </button>
            `;

            document.body.appendChild(overlay);
            document.body.appendChild(popup);

            document.getElementById('closeButton').addEventListener('click', () => {
                document.body.removeChild(overlay);
                document.body.removeChild(popup);

                document.getElementById('employeid').value = "";
                document.getElementById('employename').value = "";
                document.getElementById('clock').value = "";
                document.getElementById('time').value = "";
                document.getElementById('id_identification').value = "";
                startCamera(); // Restart camera
                startFrameCapture(); // Restart frame capture
            });
        }
    </script>

    <!-- logout -->
    <script>
        document.getElementById('logoutButton').addEventListener('click', function() {
            // Create a form dynamically
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';

            // Add CSRF token field to the form
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        });
    </script>
    
    <!-- Preview Image Company Profile -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('logoPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>    

    <!-- Table Employe -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
                "buttons": ["csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                
            $("#example2").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
                "buttons": [ "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

            $("#example3").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
                "buttons": ["csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

            $("#AdminAccount").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": true, // Enable length change option
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ], // Custom length menu options
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#SupervisorAccount").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": true, // Enable length change option
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ], // Custom length menu options
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#EmployeeAccount").DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": true, // Enable length change option
                "lengthMenu": [
                    [30, 10, 25, 50, -1],
                    [30, 10, 25, 50, "All"]
                ], // Custom length menu options
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX": true,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

    </script>

    <!-- date picker -->
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: "bootstrap4" // Optional theme, use "default" or customize as needed
            });
            $('.select3').select2({
                theme: "bootstrap4" // Optional theme, use "default" or customize as needed
            });
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate1').datetimepicker({
                format: 'DD/MM/YYYY',
            });

            $('#reservationdate2').datetimepicker({
                format: 'DD/MM/YYYY'
            });

            $('#reservationdate3').datetimepicker({
                format: 'DD/MM/YYYY'
            });

            $('#reservationdate4').datetimepicker({
                format: 'DD/MM/YYYY'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                format: 'DD/MM/YYYY HH:mm', // Format tanggal dan waktu
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                sideBySide: true, // Memastikan picker waktu dan tanggal berdampingan
                use24hours: true  // Gunakan format 24 jam
            });

            $('#reservationdatetime1').datetimepicker({
                format: 'DD/MM/YYYY HH:mm', // Format tanggal dan waktu
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                sideBySide: true, // Memastikan picker waktu dan tanggal berdampingan
                use24hours: true  // Gunakan format 24 jam
            });

            $('#reservationdatetime2').datetimepicker({
                format: 'DD/MM/YYYY HH:mm', // Format tanggal dan waktu
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                },
                sideBySide: true, // Memastikan picker waktu dan tanggal berdampingan
                use24hours: true  // Gunakan format 24 jam
            });

            //Date range picker
            $('#daterange').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            })
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function (event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function () {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function () {
                myDropzone.enqueueFile(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function (file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function (progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function () {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function () {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End

    </script>
</body>

</html>
