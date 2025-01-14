<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProvisionnalCalendarSettingsController extends Controller
{
    public function showLabels()
    {
        return Inertia::render('ConfigPage');
    }

    public function showUsers()
    {
        $users = User::with('role')->get();
        
        return Inertia::render('UsersManagementPage', [
            'users' => $users
        ]);
    }
}
