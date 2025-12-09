<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class UserController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function showlogin()
    {
        return view('auth.login');
    }

    public function store(RegisterRequest $request)
    {
        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect('/attendance');
    }

    public function login(LoginRequest $request)
    {
        $user_info = $request->validated();

        if(Auth::attempt($user_info)){
            $request->session()->regenerate();
            return redirect('/attendance');
        }

        return redirect()->route('login');
    }

    public function logout()
    {
        return view('auth.login');
    }
}
