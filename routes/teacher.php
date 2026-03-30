<?php

use App\Http\Controllers\Teachers\DashboardController;
use App\Http\Controllers\Teachers\OnlineClassController;
use App\Http\Controllers\Teachers\QuestionController;
use App\Http\Controllers\Teachers\QuizController;
use App\Http\Controllers\Teachers\Quizzes\QuizResultController;
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
        // =======================[Dashboard & Calendar]======================= //
        Route::get('/teacher/dashboard', [DashboardController::class, 'index'])
            ->name('teacher.dashboard');

        Route::get('/teacher/calendar', [DashboardController::class, 'calendar'])
            ->name('teacher.calendar');

        Route::get('/teacher/profile', [DashboardController::class, 'profile'])
            ->name('teacher.profile');
        Route::put('/teacher/password', [DashboardController::class, 'updatePassword'])
            ->name('teacher.password.update');

        // ===================[Students & Attendance Management]================ //
        Route::get('/teacher/students', [StudentController::class, 'index'])
            ->name('teacher.students.index');

        Route::post('/teacher/students/attendance', [StudentController::class, 'storeAttendance'])
            ->name('teacher.students.attendance.store');

        Route::get('/teacher/students/{student}', [StudentController::class, 'show'])
            ->name('teacher.students.show');

        Route::get('/teacher/sections', [StudentController::class, 'sections'])
            ->name('teacher.sections.index');

        // =======================[Attendance Reports]========================= //
        Route::get('/teacher/reports/attendances', [StudentController::class, 'attendanceReport'])
            ->name('teacher.reports.attendances');

        // ============================[Online Classes]========================= //
        Route::get('/teacher/online-classes/indirect', [OnlineClassController::class, 'indirectCreate'])
            ->name('teacher.online-classes.indirectCreate');
        Route::post('/teacher/online-classes/indirect', [OnlineClassController::class, 'storeIndirect'])
            ->name('teacher.online-classes.indirectStore');
        Route::resource('/teacher/online-classes', OnlineClassController::class)
            ->names('teacher.online-classes')
            ->except(['show', 'edit', 'update']);

        // =============================[Quizzes]============================= //
        Route::resource('/teacher/quizzes', QuizController::class)
            ->names('teacher.quizzes')
            ->except('show');

        Route::post('/teacher/quizzes/{quiz}/publish', [QuizController::class, 'publish'])
            ->name('teacher.quizzes.publish');
        Route::get('/teacher/quizzes-results', [QuizResultController::class, 'index'])
            ->name('teacher.quizzes.results.index');
        Route::post('/teacher/quizzes-results/{attempt}/unlock', [QuizResultController::class, 'unlock'])
            ->name('teacher.quizzes.results.unlock');

        // =============================[Questions]=========================== //
        Route::get('/teacher/questions', [QuestionController::class, 'all'])
            ->name('teacher.questions.index');

        Route::get('/teacher/quizzes/{quiz}/questions', [QuestionController::class, 'index'])
            ->name('teacher.quizzes.questions.index');
        Route::get('/teacher/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])
            ->name('teacher.quizzes.questions.create');
        Route::post('/teacher/quizzes/{quiz}/questions', [QuestionController::class, 'store'])
            ->name('teacher.quizzes.questions.store');
        Route::get('/teacher/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])
            ->name('teacher.quizzes.questions.edit');
        Route::put('/teacher/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])
            ->name('teacher.quizzes.questions.update');
        Route::delete('/teacher/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])
            ->name('teacher.quizzes.questions.destroy');
    }
);
