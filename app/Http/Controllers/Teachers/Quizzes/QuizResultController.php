<?php

namespace App\Http\Controllers\Teachers\Quizzes;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizResultController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $this->authenticatedTeacher();
        $selectedAcademicYear = trim((string) $request->input('academic_year', ''));
        $selectedQuizId = $request->integer('quiz_id');

        $attemptsQuery = QuizAttempt::query()
            ->with([
                'quiz:id,name,teacher_id,subject_id,academic_year',
                'quiz.subject:id,name',
                'student:id,name,email',
            ])
            ->whereHas('quiz', function ($query) use ($teacher): void {
                $query->where('teacher_id', $teacher->id);
            });

        if ($selectedAcademicYear !== '') {
            $attemptsQuery->whereHas('quiz', function ($query) use ($selectedAcademicYear): void {
                $query->where('academic_year', $selectedAcademicYear);
            });
        }

        if ($selectedQuizId > 0) {
            $attemptsQuery->where('quiz_id', $selectedQuizId);
        }

        $attempts = $attemptsQuery->latest('submitted_at')->latest()->get();

        $teacherQuizzesQuery = Quiz::query()
            ->where('teacher_id', $teacher->id)
            ->with('subject:id,name');

        if ($selectedAcademicYear !== '') {
            $teacherQuizzesQuery->where('academic_year', $selectedAcademicYear);
        }

        $teacherQuizzes = $teacherQuizzesQuery->latest()->get(['id', 'name', 'subject_id', 'academic_year']);

        $academicYearOptions = Quiz::query()
            ->where('teacher_id', $teacher->id)
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year');

        return view('pages.teachers.dashboard.quizzes.results.index', [
            'teacher' => $teacher,
            'attempts' => $attempts,
            'teacherQuizzes' => $teacherQuizzes,
            'academicYearOptions' => $academicYearOptions,
            'selectedAcademicYear' => $selectedAcademicYear,
            'selectedQuizId' => $selectedQuizId,
        ]);
    }

    public function unlock(QuizAttempt $attempt): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $attempt->loadMissing('quiz:id,teacher_id');
        abort_unless((int) $attempt->quiz?->teacher_id === (int) $teacher->id, 403);

        if (! $attempt->isBlocked()) {
            toastr()->error(trans('Quizzes_trans.teacher_unlock_only_blocked'));

            return redirect()->route('teacher.quizzes.results.index');
        }

        $attempt->update([
            'status' => QuizAttempt::STATUS_IN_PROGRESS,
            'blocked_at' => null,
            'blocked_reason' => null,
            'unlocked_by_teacher_id' => $teacher->id,
            'unlocked_at' => now(),
        ]);

        toastr()->success(trans('Quizzes_trans.teacher_unlock_success'));

        return redirect()->route('teacher.quizzes.results.index');
    }

    private function authenticatedTeacher(): Teacher
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }

        return $authenticatedTeacher;
    }
}
