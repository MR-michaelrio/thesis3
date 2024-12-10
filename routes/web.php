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
use App\Http\Controllers\AttendanceController;
Route::prefix('thesis')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('attendance_policy', [AttendancePolicyController::class, 'index'])->name('attendance_policy.index');
        Route::post('attendance_policy', [AttendancePolicyController::class, 'updateOrCreate'])->name('attendance_policy.updateOrCreate');

        Route::resource('department', DepartmentController::class);
        Route::post('/positions/update/', [DepartmentController::class, 'updateposition'])->name('updateposition');

        Route::resource('requestleave', RequestLeaveController::class);
        Route::put('/requestleave/update', [RequestLeaveController::class, 'update'])->name('requestleave.update');
        Route::get('/leave/remaining-quota', [RequestLeaveController::class, 'getRemainingQuota'])->name('leave.remainingQuota');

        Route::get('/attendance/data', [AttendanceController::class, 'data'])->name('attendance.data');
        Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
        Route::post('/recognize', [AttendanceController::class, 'recognize']);
        Route::post('/recognize2', [AttendanceController::class, 'processFrame']);
        
        Route::resource('attendance', AttendanceController::class);
        Route::resource('employee', EmployeeController::class);
        Route::resource('role', RoleController::class);
        Route::post('/role/admin/{id}', [RoleController::class, 'roleadmin'])->name('role.admin');
        Route::post('/role/employee/{id}', [RoleController::class, 'roleemployee'])->name('role.employee');
        Route::post('/role/supervisor/{id}', [RoleController::class, 'rolesupervisor'])->name('role.supervisor');

        Route::resource('shift', ShiftController::class);
        Route::resource('overtimes', RequestOvertimeController::class);
        Route::resource('leaves', LeaveController::class);
        Route::resource('companies', CompanyController::class);

        Route::get('/events/{userId}', [EventController::class, 'index']);
        Route::get('/external-events', [EventController::class, 'externalEvents']);
        Route::post('/events', [EventController::class, 'store'])->name('calendar.store'); // Correct route name here
        Route::post('/external-events', [EventController::class, 'storeExternalEvent'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);

        Route::get('/', function () {
            return redirect()->route('home');
        });
        Route::get('/attendance-data', [AttendanceController::class, 'getAttendanceData']);

        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/calender', [HomeController::class, 'calender'])->name('calender');
    });
// Route::get('/test', function () {
//     return view('test');
// });
Auth::routes();
});

