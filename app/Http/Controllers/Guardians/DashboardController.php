<?php

namespace App\Http\Controllers\Guardians;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.guardians.dashboard.dashboard');
    }

    public function calendar()
    {
        return view('pages.guardians.dashboard.calendar');
    }
}
