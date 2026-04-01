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
        Route::prefix('/guardian')
            ->name('guardian.')
            ->controller(DashboardController::class)
            ->group(function (): void {
                Route::get('/dashboard', 'index')->name('dashboard');
                Route::get('/dashboard/attendance', 'attendance')->name('dashboard.attendance');
                Route::get('/dashboard/financial-reports', 'financialReports')->name('dashboard.financial');
                Route::get('/profile', 'profile')->name('profile');
                Route::put('/password', 'updatePassword')->name('password.update');

                Route::get('/calendar', 'calendar')->name('calendar');
            });
    }
);
