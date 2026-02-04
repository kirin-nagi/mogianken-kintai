<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StampController extends Controller
{
    public function showAdminStamp()
    {
        return view('admin.admin_stamp_list');
    }
}
