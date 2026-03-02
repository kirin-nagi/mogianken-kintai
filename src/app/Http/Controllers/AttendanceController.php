<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Detail;
use App\Models\User;
use App\Models\Rest;
use App\Models\Approval;
use App\Http\Requests\DetailRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;



class AttendanceController extends Controller
{

    public function list(Request $request)
    {

        $currentMonth = $request->month ? Carbon::createFromFormat('Y-m', $request->month) : now()->startOfMonth();

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

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

    public function showAttendance()
    {
        $attendance = Attendance::todayForUser();

        return view('attendance.attendance', compact('attendance'));
    }

    public function start()
    {
        if(Attendance::todayForUser()){
            return redirect()->route('attendance.show');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'work_date' => today(),
            'start_work' => now(),
        ]);

        return redirect()->route('attendance.show');
    }

    public function end()
    {

        $attendance = Attendance::todayForUser();

        if(!$attendance || $attendance->isFinished() || $attendance->isOnRest())
        {
            return redirect()->route('attendance.show');
        }

        $workMinutes = now()->diffInMinutes($attendance->start_work);
        $restMinutes = $attendance->rests->sum('rest_time');

        $attendance->update([
            'end_work' => now(),
            'total_work' => $workMinutes - $restMinutes,
        ]);

        return redirect()->route('attendance.show');
    }

    public function showDetail($id)
    {
        $user = Auth::user();


        $attendance = Attendance::findOrFail($id);

        $latestApproval = Approval::where('attendance_id', $attendance->id)
        ->latest()
        ->first();

        if(!$latestApproval){
            $viewState = 'request';
        } elseif ($latestApproval->isPending()) {
            $viewState = 'pending';
        } else {
            $viewState = 'approved';
        }

        return view('attendance.detail', compact('attendance', 'user','viewState', 'latestApproval'));
    }

    public function detailStore(DetailRequest $request, $id)
    {

    $attendance = Attendance::findOrFail($id);
    $user = $attendance->user;
    $date = $attendance->work_date->format('Y-m-d');

    $approval = Approval::Create(
        [
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'reason' => $request->description,
            'status' => 0,
            'targetdate' =>$attendance->work_date->startOfDay(),
        ]
    );

    Detail::create([
        'user_id' => $user->id,
        'attendance_id' => $attendance->id,
        'approval_id' => $approval->id,
        'work_date' => $attendance->work_date,
        'start_work' => Carbon::parse("$date {$request->start_work}"),
        'end_work' => Carbon::parse("$date {$request->end_work}"),
        'rest_start' => Carbon::parse("$date {$request->rest_start}"),
        'rest_end' => Carbon::parse("$date {$request->rest_end}"),
        'rest_start2' => $request->rest_start2 ? Carbon::parse("$date {$request->rest_start2}") : null,
        'rest_end2' => $request->rest_end2 ? Carbon::parse("$date {$request->rest_end2}") : null,
        'reason' => $request->description,
    ]);

        return redirect()->route('attendance.showDetail', $id);
    }

}
