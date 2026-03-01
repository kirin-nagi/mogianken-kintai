<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function AdminLogin(LoginRequest $request)
    {
        if(! Auth::attempt($request->only('email', 'password'))){
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されておりません',
                'password' => 'ログイン情報が登録されておりません',
            ]);
        }

        $user = Auth::user();

        if($user->role != 1){
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されておりません',
                'password' => 'ログイン情報が登録されておりません',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.list');
    }
}
