<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.students.dashboard.dashboard');
    }

    public function calendar()
    {
        return view('pages.students.dashboard.calendar');
    }
}
