<?php

use App\Http\Controllers\Students\DashboardController;
use App\Http\Controllers\Students\Quizzes\QuizController;
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

        Route::get('/student/quizzes', [QuizController::class, 'index'])
            ->name('student.quizzes');
        Route::get('/student/quizzes/results', [QuizController::class, 'results'])
            ->name('student.quizzes.results');
        Route::get('/student/quizzes/{quiz}', [QuizController::class, 'show'])
            ->name('student.quizzes.show');

        Route::get('/student/profile', [DashboardController::class, 'profile'])
            ->name('student.profile');
        Route::put('/student/password', [DashboardController::class, 'updatePassword'])
            ->name('student.password.update');
    }
);
