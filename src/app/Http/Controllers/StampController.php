<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Detail;
use App\Models\Approval;
use App\Models\Attendance;

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

            $attendance->update([
                'start_work' => $detail->start_work,
                'end_work' => $detail->end_work,
            ]);

            $workMinutes = $detail->end_work
            ->diffInMinutes($detail->start_work);

            $restMinutes = $detail->rest_end
            ->diffInMinutes($detail->rest_start);

            if ($detail->rest_start2 && $detail->rest_end2) {
                $restMinutes += $detail->rest_end2
                ->diffInMinutes($detail->rest_start2);
            }

            $attendance->update([
                'rest_time' => $restMinutes,
                'total_work' => $workMinutes - $restMinutes,
            ]);
        });


        return redirect()->route('stamp.stampCorrection', $id);
    }
}