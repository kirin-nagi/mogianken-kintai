<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    public function destroy(Request $request)
    {
        $from =$request->input('from');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $from === 'admin'
        ? redirect()->route('admin.showLogin')
        : redirect()->route('login');

    }
}
