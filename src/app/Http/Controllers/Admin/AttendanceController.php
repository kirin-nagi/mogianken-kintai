<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Rest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function adminList(Request $request)
    {
        $currentDay = $request->day ? Carbon::createFromFormat('Y-m-d', $request->day)->startOfDay() : now()->startOfDay();

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

    // 仮 勤怠詳細画面
    public function adminDetailStore(DetailRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        return redirect()->route('attendance.adminDetail', $id);
    }

    // 勤怠詳細画面
    public function showAdminDetail($id)
    {
        $user = Auth::user();

        $attendance = Attendance::findOrFail($id);

    return view('admin.admin_detail', compact('attendance', 'user'));
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

    return view('admin.admin_staff_each',compact('attendances', 'currentMonth', 'prevMonth', 'nextMonth', 'dates', 'user', 'dates'));
    }

}
