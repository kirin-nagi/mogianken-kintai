<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\StampController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register',[UserController::class, 'register']);
Route::post('/register',[UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/login', [UserController::class,'showLogin'])->name('login');
Route::get('/admin/login', [AdminLoginController::class, 'showAdminLogin'])->name('admin.showLogin');
Route::post('/admin/login', [AdminLoginController::class, 'AdminLogin'])->name('admin.login');

Route::prefix('admin')->middleware(['auth', 'can:admin'])->name('admin.')
->group(function(){
Route::get('attendance/list',[AdminAttendanceController::class, 'adminList'])->name('list');
Route::get('attendance/{id}', [AdminAttendanceController::class, 'showAdminDetail'])->name('attendance.showDetail');
Route::post('attendance/{id}', [AdminAttendanceController::class, 'adminDetailStore'])->name('attendance.detail');
Route::get('staff/list', [AdminAttendanceController::class, 'showAdminStaff'])->name('staff.list');
Route::get('attendance/staff/{id}', [AdminAttendanceController::class, 'showAdminList'])->name('attendance.list');
});

Route::post('/custom-logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('custom.logout');

Route::middleware('auth')->group(function(){
    Route::get('/attendance', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
    Route::post('/attendance/start',[AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/end',[AttendanceController::class, 'end'])->name('attendance.end');
    Route::post('/attendance/rest_start',[RestController::class, 'restStart'])->name('attendance.rest_start');
    Route::post('/attendance/rest_end',[RestController::class, 'restEnd'])->name('attendance.rest_end');
    Route::get('/attendance/list',[AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.showDetail');
    Route::post('/attendance/detail/{id}', [AttendanceController::class, 'detailStore'])->name('attendance.detail');
    Route::get('/stamp_correction_request/list',[StampController::class, 'showStamp'])->name('stampList');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [StampController::class, 'showCorrection'])->name('stamp.showCorrection');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [StampController::class, 'stampCorrection'])->name('stamp.stampCorrection');
});