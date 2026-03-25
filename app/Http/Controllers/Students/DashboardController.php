<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.students.dashboard.dashboard');
    }

    public function calendar()
    {
        return view('pages.students.dashboard.calendar');
    }

    public function quizzes()
    {
        /** @var Student|null $student */
        $student = auth()->guard('student')->user();
        abort_unless($student instanceof Student, 403);

        $quizzes = Quiz::query()
            ->with(['subject:id,name', 'teacher:id,name', 'questions:id,quiz_id'])
            ->where('section_id', $student->section_id)
            ->where('status', Quiz::STATUS_PUBLISHED)
            ->latest()
            ->get();

        return view('pages.students.dashboard.quizzes', [
            'student' => $student,
            'quizzes' => $quizzes,
        ]);
    }
}
