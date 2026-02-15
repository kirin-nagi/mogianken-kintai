<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Detail;
use App\Models\Approval;

class StampController extends Controller
{
    public function showStamp()
    {
        $user = Auth::user();

        $query = Approval::query()
        ->where('status', '=',  0)
        ->whereHAs('detail')
        ->with('user');

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
        $user = Auth::user();

        if(!$user->isAdmin()){
            abort(403);
        }

        $approval = Approval::with('detail')
        ->findOrFail($id);

        $detail = $approval->detail;

        return view('stamp.stamp_correction', compact('user', 'approval', 'detail' ));
    }

    public function stampCorrection($attendance_correct_request_id)
    {
        $approval = Approval::findOrFail($attendance_correct_request_id);

        if($approval->status !== 0){
            return redirect()->route('stamp.showCorrection', $approval->id);
        }

        $approval->update([
            'status' => 1
        ]);

        return redirect()->route('stamp.showCorrection', $approval->id);
    }
}
