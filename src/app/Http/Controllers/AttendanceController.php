<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceController extends Controller
{

    // 勤怠打刻画面
    public function showattendance()
    {
        $attendance = Attendance::todayForUser();

        return view('attendance.attendance', compact('attendance'));
    }

    public function start()
    {
        if(Attendance::todayForUser()){
            return redirect()->route('attendance.attendance');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'start_time' => now(),
        ]);

        return redirect()->route('attendance.attendance');
    }

    
}
