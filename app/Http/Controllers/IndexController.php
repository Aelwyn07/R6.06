<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function show()
    {
        $userRoleLevel = Auth::user()->role->level;
        switch ($userRoleLevel) {
            case 0:
                return redirect()->route('provisionnal_calendar.groups');
            case 1:
            case 2:
                return redirect()->route('provisionnal_calendar');
            default:
                return redirect()->route('login');
        }
    }
}
