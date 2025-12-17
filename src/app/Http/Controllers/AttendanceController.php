<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;

class AttendanceController extends Controller
{
    public function showattendance()
    {
        return view('attendance.attendance');
    }

}
