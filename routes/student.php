<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            'localeCookieRedirect',
            'localizationRedirect',
            'localeViewPath',
            'auth:student',
        ],
    ],
    function (): void {
        Route::view('/student/dashboard', 'pages.students.dashboard.dashboard')
            ->name('student.dashboard');
    }
);
