<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function showattendance()
    {
        return view('attendance.attendance');
    }

    public function attendance()
    {
        $attendance = Attendance::where('user_id', auth()->id())->where('work_date', today())->first();


    }

}
