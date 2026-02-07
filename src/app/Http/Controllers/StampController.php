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

        $query = Approval::with('user')
        ->where('status', 0)
        ->join('users', 'approvals.user_id', '=', 'users.id');

        if(!$user->isAdmin())
            {
                $query->where('approvals.user_id', $user->id);
            }

            $approvals = $query
                ->orderBy('users.name', 'asc')
                ->orderBy('approvals.targetdate', 'asc')
                ->select('approvals.*')
                ->get();

        return view('stamp.stamp_list', compact('approvals'));
    }

    public function showCorrection()
    {
        return view('stamp.stamp_correction');
    }
}
