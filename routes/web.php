<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RequestOvertimeController;
use App\Http\Controllers\RequestLeaveController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AttendancePolicyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AttendanceController;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::middleware(['auth'])->group(function () {
    Route::get('attendance_policy', [AttendancePolicyController::class, 'index'])->name('attendance_policy.index');
    Route::post('attendance_policy', [AttendancePolicyController::class, 'updateOrCreate'])->name('attendance_policy.updateOrCreate');

    Route::resource('department', DepartmentController::class);
    Route::post('/positions/update/', [DepartmentController::class, 'updateposition'])->name('updateposition');
    Route::post('/position/delete/', [DepartmentController::class, 'deleteposition'])->name('deleteposition');
    Route::post('/store-position', [DepartmentController::class, 'storePosition'])->name('storeposition');
    Route::get('/positions/{id_department}', [DepartmentController::class, 'getPositions'])->name('getpositions');

    Route::resource('requestleave', RequestLeaveController::class);
    Route::put('/requestleave/update', [RequestLeaveController::class, 'update'])->name('requestleave.update');
    Route::get('/leave/remaining-quota', [RequestLeaveController::class, 'getRemainingQuota'])->name('leave.remainingQuota');

    Route::resource('attendance', AttendanceController::class);
    Route::get('/attendancedata', [AttendanceController::class, 'data'])->name('attendance.data');    
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/recognize', [AttendanceController::class, 'recognize'])->name('recognize');
    Route::get('/attendance-data', [AttendanceController::class, 'getAttendanceData'])->name('attendance-data');
    Route::post('/attendance-manual', [AttendanceController::class, 'manualattendance'])->name('attendance-manual');

    Route::resource('employee', EmployeeController::class);
    Route::post('/employee/statusupdate/{id}', [EmployeeController::class, 'statusupdate'])->name('employee.statusupdate');
    Route::get('get-department-positions/{departmentId}', [EmployeeController::class, 'getDepartmentPositions'])->name('getDepartmentPositions');
    Route::get('/getSupervisorsByDepartment/{departmentId}', [EmployeeController::class, 'getSupervisorsByDepartment'])->name('getSupervisorsByDepartment');
    Route::get('/get-department-details', [EmployeeController::class, 'getDepartmentDetails'])->name('getDepartmentDetails');

    Route::resource('role', RoleController::class);
    Route::post('/role/admin/{id}', [RoleController::class, 'roleadmin'])->name('role.admin');
    Route::post('/role/employee/{id}', [RoleController::class, 'roleemployee'])->name('role.employee');
    Route::post('/role/supervisor/{id}', [RoleController::class, 'rolesupervisor'])->name('role.supervisor');

    Route::resource('shift', ShiftController::class);

    Route::resource('overtimes', RequestOvertimeController::class);
    Route::get('/overtime/clock/{date}', [RequestOvertimeController::class, 'getOvertimeData'])->name('overtime.clock');
    Route::put('/requestovertime/update', [RequestOvertimeController::class, 'update'])->name('requestovertime.update');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', [HomeController::class, 'calendar'])->name('calendar');
    Route::resource('leaves', LeaveController::class);
    Route::resource('companies', CompanyController::class);

    Route::get('/events', [EventController::class, 'index'])->name('calendar.get');
    Route::post('/events', [EventController::class, 'store'])->name('calendar.store'); // Correct route name here
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('calendar.delete');

    Route::resource('invoice', InvoiceController::class);

    Route::get('/', function () {
        return redirect()->route('home');
    });

    
});

Route::get('/test', function () {
    $id_identification = "31730";
    $id_employee = User::where('identification_number',$id_identification)->with('employee')->first();
    $id_employee = $id_employee->employee->id_employee;
    
    return $id_employee;
    // return Hash::make("123123123");
});

Auth::routes();

Route::post('logout', [HomeController::class, 'logout'])->name('logout');

