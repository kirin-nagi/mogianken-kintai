<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceController extends Controller
{

    // 勤怠打刻画面
    public function showAttendance()
    {
        $attendance = Attendance::todayForUser();

        return view('attendance.attendance', compact('attendance'));
    }

    // 出勤
    public function start()
    {
        if(Attendance::todayForUser()){
            return redirect()->route('attendance.show');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'start_work' => now(),
        ]);

        return redirect()->route('attendance.show');
    }

    // 退勤
    public function end()
    {

        $attendance = Attendance::todayForUser();

        if(!$attendance || $attendance->isFinished() || $attendance->isOnRest())
        {
            return redirect()->route('attendance.show');
        }

        $attendance->update([
            'end_work' => now(),
        ]);

        return redirect()->route('attendance.thanks');
    }

    // 勤怠一覧確認用
    public function showList()
    {
        return view('attendance.list');
    }
}
