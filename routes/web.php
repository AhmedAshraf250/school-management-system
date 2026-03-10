<?php

use App\Http\Controllers\Classrooms\ClassroomController;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Libraries\LibraryController;
use App\Http\Controllers\Questions\QuestionController;
use App\Http\Controllers\Quizzes\QuizController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Sections\SectionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Students\AttendanceController;
use App\Http\Controllers\Students\FeeController;
use App\Http\Controllers\Students\FeeInvoiceController;
use App\Http\Controllers\Students\GraduationController;
use App\Http\Controllers\Students\OnlineClassController;
use App\Http\Controllers\Students\PaymentController;
use App\Http\Controllers\Students\ProcessingFeeController;
use App\Http\Controllers\Students\PromotionController;
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\Teachers\TeacherController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

require __DIR__.'/auth.php';

Route::group([
    'middleware' => ['guest'],
], function () {

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
        Route::get('students/classrooms/{id}', [StudentController::class, 'getClassrooms'])
            ->name('students.getClassrooms');
        Route::get('students/sections/{id}', [StudentController::class, 'getSections'])
            ->name('students.getSections');
        Route::post('students/{student}/upload_attachment', [StudentController::class, 'uploadAttachments'])
            ->name('students.uploadAttachments');
        Route::get('students/{student}/download_attachment/{attachmentId}', [StudentController::class, 'downloadAttachment'])
            ->name('students.downloadAttachment');
        Route::delete('students/{student}/delete_attachment/{attachmentId}', [StudentController::class, 'deleteAttachment'])
            ->name('students.deleteAttachment');
        Route::resource('students', StudentController::class);

        // ==============================[Students.Promotion]============================ //
        Route::resource('promotions', PromotionController::class)->only(['index', 'create', 'store', 'destroy']);

        // ==============================[Students.Graduation]============================ //
        Route::post('graduates/bulk', [GraduationController::class, 'graduateBatch'])->name('graduates.bulk');
        Route::resource('graduates', GraduationController::class)->only(['index', 'store', 'destroy']);

        // ==============================[Students.Fees]============================ //
        Route::resource('fees', FeeController::class);
        Route::resource('fee-invoices', FeeInvoiceController::class);
        Route::resource('receipts', ReceiptController::class);
        Route::resource('processing-fees', ProcessingFeeController::class);
        Route::resource('student-payments', PaymentController::class);

        // ==============================[Students.Attendances]============================ //
        Route::resource('attendances', AttendanceController::class);

        // ==============================[Students.OnlineClasses]============================ //
        Route::get('online-classes/indirect', [OnlineClassController::class, 'indirectCreate'])
            ->name('online-classes.indirectCreate');
        Route::post('online-classes/indirect', [OnlineClassController::class, 'storeIndirect'])
            ->name('online-classes.indirectStore');
        Route::resource('online-classes', OnlineClassController::class);

        // ==============================[Subjects]============================ //
        Route::resource('subjects', SubjectController::class);

        // ==============================[Quizzes]============================ //
        Route::resource('quizzes', QuizController::class);

        // ==============================[Questions]============================ //
        Route::resource('questions', QuestionController::class);

        // ==============================[library]============================ //
        Route::get('libraries/{library}/download', [LibraryController::class, 'download'])->name('libraries.download');
        Route::resource('libraries', LibraryController::class);

        // ==============================[Settings]============================ //
        Route::resource('settings', SettingController::class)->only(['index', 'update']);
    }

    // // ==============================[profile]============================ //
    // Route::middleware('auth')->group(function () {
    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

);
