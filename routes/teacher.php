<?php

use App\Http\Controllers\Teachers\DashboardController;
use App\Http\Controllers\Teachers\StudentController;
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
        Route::get('/teacher/dashboard', [DashboardController::class, 'index'])
            ->name('teacher.dashboard');

        Route::get('/teacher/calendar', [DashboardController::class, 'calendar'])
            ->name('teacher.calendar');

        Route::get('/teacher/students', [StudentController::class, 'index'])
            ->name('teacher.students.index');

        Route::get('/teacher/students/{student}', [StudentController::class, 'show'])
            ->name('teacher.students.show');
    }
);
