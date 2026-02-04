<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StampController extends Controller
{
    public function showStamp()
    {
        return view('stamp.stamp_list');
    }
}
