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
            'auth:teacher',
        ],
    ],
    function (): void {
        Route::view('/teacher/dashboard', 'pages.teachers.dashboard.dashboard')
            ->name('teacher.dashboard');
    }
);
