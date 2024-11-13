<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RequestOvertimeController;


Route::resource('overtimes', RequestOvertimeController::class);
Route::resource('leaves', LeaveController::class);
Route::resource('companies', CompanyController::class);

Route::get('/events/{userId}', [EventController::class, 'index']);
Route::get('/external-events', [EventController::class, 'externalEvents']);
Route::post('/events', [EventController::class, 'store'])->name('calendar.store'); // Correct route name here
Route::post('/external-events', [EventController::class, 'storeExternalEvent'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::delete('/events/{id}', [EventController::class, 'destroy']);

Route::get('/test', function () {
    return view('test');
});

Route::get('/calender', function () {
    return view('calender');
});

Route::get('/employe-data', function () {
    return view('employe-data');
});
Route::get('/employee-add', function () {
    return view('employee-add');
});

Route::get('/attendance', function () {
    return view('attendance/attendance');
});
Route::get('/attendance-data', function () {
    return view('attendance/attendance-data');
});
Route::get('/attendance-policy', function () {
    return view('attendance/attendance-policy');
});

Route::get('/approval-leave-data', function () {
    return view('approval/leave-data');
});
Route::get('/approval-overtime-data', function () {
    return view('approval/overtime-data');
});

Route::get('/request-leave', function () {
    return view('request/leave-request');
});
Route::get('/request-overtime', function () {
    return view('request/overtime-request');
});

Route::get('/setting-role', function () {
    return view('settings/role-management');
});
Route::get('/company-profile', function () {
    return view('settings/company-profile');
});

Route::get('/department-data', function () {
    return view('settings/department-data');
});
Route::get('/department-add', function () {
    return view('settings/department-add');
});

Route::get('/shift-data', function () {
    return view('settings/shift-data');
});

Route::get('/facerecognition-add', function () {
    return view('settings/facerecognition-add');
});

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
