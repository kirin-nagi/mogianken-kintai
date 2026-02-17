<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Rest;
use App\Models\Approval;
use App\Http\Requests\DetailRequest;
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

    // 勤怠詳細画面
    public function showDetail($id)
    {
        $user = Auth::user();


        $attendance = Attendance::findOrFail($id);

        // 修正申請
        $approval = Approval::where('attendance_id',$attendance->id)
        ->latest()
        ->first();

        if(!$approval){
            $viewState = 'request';
        } elseif ($approval->isPending()) {
            $viewState = 'pending';
        } elseif ($approval->isApproved()) {
            $viewState = 'approved';
        }


        if ($approval && $approval->detail && $approval->detail->work_date){
            $baseDate =  Carbon::parse($approval->detail->work_date);
        } elseif ($attendance->start_work){
            $baseDate = Carbon::parse($attendance->start_work);
        } else {
            $baseDate = null;
        };

            $year = $baseDate ? $baseDate->year:  null;
            $monthDay = $baseDate ? $baseDate->format('m-d') : null;

        return view('attendance.detail', compact('attendance', 'approval', 'user','viewState', 'year', 'monthDay'));
    }

    public function detailStore(DetailRequest $request, $id)
    {
    // 勤怠レコードを取得
    $attendance = Attendance::findOrFail($id);

    [$month, $day] = explode('-', $request->month_day);

    $date = Carbon::create(
        (int)$request->year, (int)$month, (int)$day
    );

    // DBの保存
    $attendance->update([
        'start_work' => Carbon::parse($date->format('Y-m-d') . ' ' . $request->start_work),
        'end_work' => Carbon::parse($date->format('Y-m-d') . ' ' . $request->end_work),
        'rest_start' => Carbon::parse($date->format('Y-m-d') . ' ' . $request->rest_start),
        'rest_end' => Carbon::parse($date->format('Y-m-d') . ' ' . $request->rest_end),
        'rest_start2' => $request->rest_start2 ? Carbon::parse($date->format('Y-m-d') . ' ' . $request->rest_start2) : null,
        'rest_end2' => $request->rest_end2 ? Carbon::parse($date->format('Y-m-d') . ' ' . $request->rest_end2) : null,
        'description' => $request->input('description'),
    ]);

        return redirect()->route('attendance.showDetail', $id);
    }

}
