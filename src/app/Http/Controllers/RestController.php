<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;

class RestController extends Controller
{
    public function start()
    {
        // 休憩開始
        $attendance = Attendance::todayForUser();

        // 出勤してなくて、退勤済なら何もしない
        if(!$attendance || $attendance->isFinished())
        {
            return redirect()->route('attendance.show');
        }

        // 休憩中
        if($attendance->isOnRest())
        {
            return redirect()->route('attendance.show');
        }

        Rest::create([
            'attendance_id' => $attendance->id,
            'rest_start' => now(),
        ]);

        return redirect()->route('attendance.show');
    }

    // 休憩終わり
    public function end()
    {
        $attendance = Attendance::todayForUser();

        if(!$attendance || $attendance->isFinished())
        {
            return redirect()->route('attendance.show');
        }

        // 最新の休憩
        $latestRest = $attendance->rests()->latest()->first();

        if(!$latestRest || $latestRest->rest_end)
        {
            return redirect()->route('attendance.show');
        }

        $latestRest->update([
            'rest_end' => now(),
        ]);

        return redirect()->route('attendance.show');
    }

}
