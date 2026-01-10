<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;


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

        // 月の範囲
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // 月の全日付
        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

        // 勤怠取得
        $attendances = Attendance::where('user_id', auth()->id())
        ->whereBetween('start_work', [$startOfMonth, $endOfMonth])
        ->with('rests')
        ->get()
        ->keyBy(fn ($a) => $a->start_work->format('Y-m-d'));

        $dates = [];
        for($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay())
        {
            $dates[] = $date->copy();
        }

        $user = Auth::user();

        return view('attendance.list', compact('attendances', 'currentMonth', 'prevMonth', 'nextMonth', 'dates', 'user'));
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

    public function detail($date)
    {
        $attendance = Attendance::whereDate('work_date', $date)
        ->where('user_id', auth()->id())
        ->first();

        return view('attendance.detail', compact('date', 'attendance'));
    }
}
