<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function AdminLogin(LoginRequest $request)
    {
        if(! Auth::attempt($request->only('email', 'password'))){
            return back();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.list');
    }
}
