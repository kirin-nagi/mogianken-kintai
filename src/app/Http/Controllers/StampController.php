<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Detail;
use App\Models\Approval;
use App\Models\Attendance;
use App\Models\Rest;

class StampController extends Controller
{
    public function showStamp(Request $request)
    {
        $user = Auth::user();

        $status = $request->get('tab') === 'approval' ? 1:0;

        $query = Approval::query()
        ->where('status', $status)
        ->whereHas('detail')
        ->with('user', 'attendance');

        if(!$user->isAdmin())
            {
                $query->where('user_id', $user->id);
            }

            $approvals = $query
                ->get()
                ->sortBy([
                    fn($a, $b)=> $a->user->name <=> $b->user->name,
                    fn($a, $b)=> $a->targetdate <=> $b->targetdate,
                ]);

        return view('stamp.stamp_list', compact('approvals'));
    }
//
    public function showCorrection($id)
    {
        $approval = Approval::where('id', $id)
        ->whereHas('detail')
        ->firstOrFail();

        return view('stamp.stamp_correction', [
            'approval' =>$approval,
            'detail' => $approval->detail
        ]);
    }

    public function stampCorrection($id)
    {
        DB::transaction(function () use ($id){

            $approval = Approval::with('detail')->findOrFail($id);

            if ($approval->status !== 0){
                return;
            }

            $approval->update([
                'status' => 1
            ]);

            $detail = $approval->detail;
            $attendance = Attendance::findOrFail($approval->attendance_id);

            $workMinutes = $detail->end_work
            ->diffInMinutes($detail->start_work);

            $restMinutes = 0;

            if($detail->rest_start && $detail->rest_end){
                $minutes = $detail->rest_end
                ->diffInMinutes($detail->rest_start);

                $restMinutes += $minutes;

                Rest::updateOrCreate(
                    [
                        'attendance_id' => $attendance->id,
                        'rest_start' => $detail->rest_start,
                    ],
                    [
                        'rest_end' => $detail->rest_start,
                        'rest_time' => $minutes,
                    ]
                );
            }

            if($detail->rest_start2 && $detail->rest_end2){
                $minutes= $detail->rest_end2
                ->diffInMinutes($detail->rest_start2);

                $restMinutes += $minutes;

                Rest::updateOrCreate(
                    [
                        'attendance_id' => $attendance->id,
                        'rest_start' => $detail->rest_start2,
                    ],
                    [
                        'rest_end' => $detail->rest_end2,
                        'rest_time' => $minutes,
                    ]
                );
            }

            $attendance->update([
                'start_work' => $detail->start_work,
                'end_work' => $detail->end_work,
                'rest_time' => $restMinutes,
                'total_work' => $workMinutes - $restMinutes,
            ]);
        });


        return redirect()->route('stamp.showCorrection', $id);
    }

    public function storeCorrection(Request $request)
    {
        DB::transaction(function () use ($request) {

            $attendanceId = $request->attendance_id;

            $approval = Approval::create([
                'user_id' => auth()->id(),
                'attendance_id' => $attendanceId,
                'status' => 0,
            ]);

            $date = Carbon::parse($request->work_date)->format('Y-m-d');

            Detail::create([
                'approval_id' => $approval->id,
                'attendance_id' => $attendanceId,
                'user_id' => auth()->id(),
                'work_date' => $request->work_date,

                'start_work' => Carbon::parse("$date {$request->start_work}"),
                'end_work' => Carbon::parse("$date {$request->end_work}"),

                'rest_start' => Carbon::parse("$date {$request->rest_start}"),
                'rest_end' => Carbon::parse("$date {$request->rest_end}"),
                'rest_start2' => $request->rest_start2 ? Carbon::parse("$date {$request->rest_start2}") : null,
                'rest_end2' => $request->rest_end2 ? Carbon::parse("$date {$request->rest_end2}") : null,
                'reason' => $request->description,
            ]);
        });

        return redirect()->route('stamp.showCorrection');
    }
}