<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class GroupsController extends Controller
{
    public function show()
    {
        return Inertia::render('GroupsPage');
    }
}
