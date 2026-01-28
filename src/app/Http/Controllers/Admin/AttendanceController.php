<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function adminList(Request $request)
    {
        $currentDay = $request->Day ? Carbon::createFromFormat('Y-m-d', $request->Day) : now()->startOfDay();

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

    // 仮 勤怠申請画面
    public function adminDetailStore(DetailRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        return redirect()->route('attendance.adminDetail', $id);
    }

    public function showAdminDetail($id)
    {
        $user = Auth::user();

        $attendance = Attendance::findOrFail($id);

    return view('admin.admin_detail', compact('attendance', 'user'));
    }


}
