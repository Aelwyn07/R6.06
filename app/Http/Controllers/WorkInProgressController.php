<?php

namespace App\Http\Controllers;

class WorkInProgressController extends Controller
{
    public function show()
    {
        return redirect()->route('provisionnal_calendar');
    }
}
