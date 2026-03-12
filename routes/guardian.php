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
            'auth:guardian',
        ],
    ],
    function (): void {
        Route::view('/guardian/dashboard', 'pages.guardians.dashboard.dashboard')
            ->name('guardian.dashboard');
    }
);
