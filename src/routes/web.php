<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\Admin\StampController as AdminStampController;
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

Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
Route::prefix('admin')->middleware(['auth', 'can:admin'])->name('admin.')
->group(function(){
    Route::get('attendance/list',[AdminAttendanceController::class, 'adminList'])->name('list');
});

// 後からmiddlewareに入れる
Route::get('/attendance', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
Route::post('/attendance/start',[AttendanceController::class, 'start'])->name('attendance.start');
Route::post('/attendance/end',[AttendanceController::class, 'end'])->name('attendance.end');
Route::post('/attendance/rest_start',[AttendanceController::class, 'restStart'])->name('attendance.rest_start');
Route::post('/attendance/rest_end',[AttendanceController::class, 'restEnd'])->name('attendance.rest_end');
Route::post('/attendance/thanks',[AttendanceController::class, 'thanks'])->name('attendance.thanks');
Route::get('/attendance/list',[AttendanceController::class, 'list'])->name('attendance.list');
Route::get('/attendance/detail/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.showDetail');
Route::post('/attendance/detail/{id}', [AttendanceController::class, 'detailStore'])->name('attendance.detail');
Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'showAdminDetail'])->name('attendance.adminShowDetail');
Route::post('/admin/attendance/{id}', [AdminAttendanceController::class, 'adminDetailStore'])->name('attendance.adminDetail');
Route::get('/admin/staff/list', [AdminAttendanceController::class, 'showAdminStaff'])->name('admin.staffList');
Route::get('/admin/attendance/staff/{id}', [AdminAttendanceController::class, 'showAdminList'])->name('admin.attendanceList');
// middlewareで管理者と一般区別する（url一緒だから）
Route::get('/admin/stamp_correction_request/list',[AdminStampController::class, 'showAdminStamp'])->name('admin.stampList');
Route::get('/stamp_correction_request/list',[StampController::class, 'showStamp'])->name('stampList');
