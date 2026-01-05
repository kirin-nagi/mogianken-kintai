<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;

class RestController extends Controller
{
    public function restStart()
    {
        // 休憩開始
        $attendance = Attendance::todayForUser();

        // 出勤してなくて、退勤済なら何もしない
        if(!$attendance || $attendance->isFinished() || $attendance->isOnRest())
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
    public function restEnd()
    {
        $attendance = Attendance::todayForUser();

        if(!$attendance) {
            return redirect()->route('attendance.show');
        }

        // 最新の休憩
        $rest = $attendance->rests()->whereNull('rest_end')->latest()->first();

        if(!$rest) {
            return redirect()->route('attendance.show');
        }

        $minutes = now()->diffInMinutes($rest->rest_start);

        $rest->update([
            'rest_end' => now(),
            'rest_time' =>$minutes,
        ]);

        return redirect()->route('attendance.show');
    }
}
