<?php

use App\Http\Controllers\Students\DashboardController;
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
        Route::get('/student/dashboard', [DashboardController::class, 'index'])
            ->name('student.dashboard');

        Route::get('/student/calendar', [DashboardController::class, 'calendar'])
            ->name('student.calendar');

        Route::get('/student/quizzes', [DashboardController::class, 'quizzes'])
            ->name('student.quizzes');

        Route::get('/student/profile', [DashboardController::class, 'profile'])
            ->name('student.profile');
        Route::put('/student/password', [DashboardController::class, 'updatePassword'])
            ->name('student.password.update');
    }
);
