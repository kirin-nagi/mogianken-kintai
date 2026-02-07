<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Detail;
use App\Models\Approval;

class StampController extends Controller
{
    public function showStamp()
    {
        $approvals = Approval::where('status', 0)->get();

        $approvals = Approval::with('user')
        ->where('status', 0)
        ->join('users', 'approvals.user_id', '=', 'users.id')
        ->orderBy('users.name', 'asc')
        ->orderBy('approvals.targetdate', 'asc')
        ->select('approvals.*')
        ->get();

        return view('stamp.stamp_list', compact('approvals'));
    }
}
