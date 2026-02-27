<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Approval;
use App\Models\User;
use App\Models\Rest;
use App\Models\Detail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function adminList(Request $request)
    {
        $currentDay = $request->day ?Carbon::createFromFormat('Y-m-d', $request->day)->startOfDay() : now()->startOfDay();

        $prevDay = $currentDay->copy()->subDay();
        $nextDay = $currentDay->copy()->addDay();


        $startOfDay = $currentDay->copy()->startOfDay();
        $endOfDay = $currentDay->copy()->endOfDay();

        $attendances = Attendance::whereBetween('start_work', [$startOfDay, $endOfDay])
        ->with(['user', 'rests'])
        ->get()
        ->groupBy('user_id');
        // ↑複数表示させたいからkeyじゃなくてgroup

        return view('admin.admin_list', compact('attendances', 'currentDay', 'prevDay','nextDay'));
    }

    //  勤怠詳細画面
    public function adminDetailStore(DetailRequest $request, $id)
    {
    DB::transaction(function () use ($request,$id) {

        // 勤怠レコードを取得
    $attendance = Attendance::findOrFail($id);
    $user = $attendance->user;
    $date = $attendance->work_date->format('Y-m-d');

    $approval = Approval::where('attendance_id', $attendance->id)
    ->letest()
    ->first();

    if($approval){
        $approval->update(['status' => 1]);
    } else {
        $approval = Approval::create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'status' => 1,
            'targetdate' =>$attendance->work_date,
        ]);
    }

    $detail = Detail::updateOrCreate(
        [
            'approval_id' => $approval->id,
        ],
        [
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
        ]
    );

    $workMinutes = $detail->end_work->diffInMinutes($detail->start_work);
    $reatMinutes = 0;

    Rest::where('attendance_id', $attendance->id)->delete();

    if($detail->rest_start && $detail->rest_end) {
        $minutes = $detail->rest_end->diffInMinutes($detail->rest_start);
        $restMinutes += $minutes;

        Rest::create([
            'attendance_id' => $attendance->id,
            'rest_start' => $detail->rest_start,
            'rest_end' => $detail->rest_end,
            'rest_time' => $minutes,
        ]);
    }

    if($detail->rest_start2 && $detail->rest_end2) {
        $minutes = $detail->rest_end2->diffInMinutes($detail->rest_start2);
        $restMinutes = 0;

        Rest::create([
            'attendance_id' => $attendance->id,
            'rest_start' => $detail->rest_start2,
            'rest_end' => $detail->rest_end2,
            'rest_time' => $minutes,
        ]);
    }

    // DBの保存
    $attendance->update([
        'start_work' => $detail->start_work,
        'end_work' =>   $detail->end_work,
        'rest_time' => $reatMinutes,
        'total_work' => $workMinutes - $restMinutes,
        'description' => $request->description,
    ]);
    });

        return redirect()->route('admin.attendanceList');
    }

    // 勤怠詳細画面
    public function showAdminDetail($id)
    {
    $user = Auth::user();


        $attendance = Attendance::findOrFail($id);

        // 修正申請
        $approval = Approval::where('attendance_id', $attendance->id)
        ->latest()
        ->first();

        if(!$approval){
            $viewState = 'request';
        } elseif ($approval->isPending()) {
            $viewState = 'pending';
        } else {
            $viewState = 'approved';
        }

        return view('admin.admin_detail', [
            'attendance' => $attendance,
            'approval' => $approval,
            'viewState' => $viewState,
            'canEdit' => auth()->user()->isAdmin()
            ]);
    }

    // スタッフ一覧画面
    public function showAdminStaff()
    {
        $users = User::where('role', 0)->get();

    return view('admin.admin_staff', compact('users'));
    }

    // スタッフ別勤怠一覧
    public function showAdminList(Request $request, $id)
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
        $attendances = Attendance::where('user_id', $id)
        ->whereBetween('start_work', [$startOfMonth, $endOfMonth])
        ->with('rests')
        ->get()
        ->groupBy(fn ($a) => $a->start_work->format('Y-m-d'));

        $dates = [];
        for($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay())
        {
            $dates[] = $date->copy();
        }

        $user = User::findOrFail($id);

    return view('admin.admin_staff_list',compact('attendances', 'currentMonth', 'prevMonth', 'nextMonth', 'dates', 'user', 'dates'));
    }

}
