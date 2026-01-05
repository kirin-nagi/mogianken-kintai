<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceController extends Controller
{

    // 上部の月選択
    public function list(Request $request)
    {
        // 見たい月表示
        $currentMonth = $request->month ? Carbon::createFromFormat('Y-m', $request->month) : now()->startOfMonth();

        // 前月・翌月
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // ログインしてるユーザー
        $attendances = Attendance::where('user_id',auth()->id())
        ->whereBetween('start_work',[
            $currentMonth->copy()->startOfMonth(),
            $currentMonth->copy()->endOfMonth(),
        ])
        ->orderBy('start_work')
        ->get();

        return view('attendance.list', compact('attendances', 'currentMonth', 'prevMonth', 'nextMonth'));
    }

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

        $workMinutest = now()->diffInMinutest($attendance->start_work);
        $restMinutest = $attendance->rests->sum('rest_time');

        $attendance->update([
            'end_work' => now(),
            'total_work' => $workMinutest - $restMinutest,
        ]);

        return redirect()->route('attendance.thanks');
    }
}
