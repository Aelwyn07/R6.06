<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProvisionnalCalendarReaderController extends Controller
{
    public function show()
    {
        return Inertia::render('ReaderCalendarPage');
    }
}
