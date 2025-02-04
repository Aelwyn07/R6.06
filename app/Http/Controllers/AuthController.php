<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return Inertia::render('LoginPage');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $userRoleLevel = $user->role->level;

            switch ($userRoleLevel) {
                case 0:
                    return redirect()->route('provisionnal_calendar.groups');
                case 1:
                case 2:
                    return redirect()->route('provisionnal_calendar');
                default:
                    break;
            }
        }

        return redirect()->route('login')->withErrors([
            'username' => 'Identifiants incorrects.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
