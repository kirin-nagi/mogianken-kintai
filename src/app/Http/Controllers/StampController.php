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
        $approval = Approval::findOrFail($id);

        if($approval->status === 0){

        $approval->update([
            'status' => 1
        ]);
        }


        return redirect()->route('stamp.showCorrection', $approval->id);
    }
}
