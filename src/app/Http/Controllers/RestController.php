<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;

class RestController extends Controller
{
    public function restStart()
    {

        $attendance = Attendance::todayForUser();

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

    public function restEnd()
    {
        $attendance = Attendance::todayForUser();

        if(!$attendance) {
            return redirect()->route('attendance.show');
        }

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
