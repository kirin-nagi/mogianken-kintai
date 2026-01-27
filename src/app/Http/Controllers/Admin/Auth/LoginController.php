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
        $credentials = $request->only('email', 'password');

        if(Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 1,
        ])){
            $request->session()->regenerate();
            return redirect()->route('admin.list');
        }
        return back();
    }
}
