<?php

use App\Http\Controllers\Guardians\DashboardController;
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
        Route::get('/guardian/dashboard', [DashboardController::class, 'index'])
            ->name('guardian.dashboard');

        Route::get('/guardian/calendar', [DashboardController::class, 'calendar'])
            ->name('guardian.calendar');
    }
);
