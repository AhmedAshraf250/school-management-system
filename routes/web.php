<?php

use App\Http\Controllers\Classrooms\ClassroomController;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sections\SectionController;
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Teachers\TeacherController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

require __DIR__.'/auth.php';

Route::group(['middleware' => ['guest']], function () {

    Route::get('/', function () {
        return view('auth.login');
    });
});

// ==============================[Translated Pages]============================ //
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth'],
    ],
    function () {

        // ==============================[dashboard]============================ //
        Route::get('/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('dashboard');

        // ==============================[Grades]============================ //
        Route::resource('grades', GradeController::class);

        // ==============================[Classrooms]============================ //
        Route::post('classrooms/delete_all', [ClassroomController::class, 'delete_all'])->name('classrooms.delete_all');
        Route::get('classrooms/filter_classes', [ClassroomController::class, 'filter_classes'])->name('classrooms.filter_classes');
        Route::resource('classrooms', ClassroomController::class);

        // ==============================[Sections]============================ //
        Route::get('sections/classes/{id}', [SectionController::class, 'getclasses'])->name('sections.getclasses');
        Route::resource('sections', SectionController::class);

        // ==============================[Guardians]============================ //
        Route::view('guardians', 'pages.guardians.guardian')->name('guardians');

        // ==============================[Teachers]============================ //
        Route::resource('teachers', TeacherController::class)->except('show');

        // ==============================[Students]============================ //
        Route::get('students/classrooms/{id}', [StudentController::class, 'getClassrooms'])->name('students.getClassrooms');
        Route::get('students/sections/{id}', [StudentController::class, 'getSections'])->name('students.getSections');
        Route::resource('students', StudentController::class);
    }

    // // ==============================[profile]============================ //
    // Route::middleware('auth')->group(function () {
    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

);
