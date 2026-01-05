<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;

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
Route::get('/admin/login', [UserController::class, 'showAdminLogin']);

Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// 後からmiddlewareに入れる
Route::get('/attendance', [AttendanceController::class, 'showAttendance'])->name('attendance.show');
Route::post('/attendance/start',[AttendanceController::class, 'start'])->name('attendance.start');
Route::post('/attendance/end',[AttendanceController::class, 'end'])->name('attendance.end');
Route::post('/attendance/rest_start',[AttendanceController::class, 'restStart'])->name('attendance.rest_start');
Route::post('/attendance/rest_end',[AttendanceController::class, 'restEnd'])->name('attendance.rest_end');
Route::post('/attendance/thanks',[AttendanceController::class, 'thanks'])->name('attendance.thanks');


Route::get('/attendance/list',[AttendanceController::class, 'list'])->name('attendance.list');