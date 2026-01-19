<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function today()
    {
        $today = Carbon::today();

        $attendance = Attendance::with(['user', 'rests'])
        ->whereDate('work_date', $today)
        ->get();

        return view('admin.list', compact('attendance', 'today'));
    }
}
