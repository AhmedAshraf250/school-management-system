<?php

namespace App\Http\Controllers\Students\Quizzes;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $student = $this->authenticatedStudent();
        $selectedAcademicYear = trim((string) $request->input('academic_year', ''));

        $quizzesQuery = Quiz::query()
            ->with([
                'subject:id,name',
                'teacher:id,name',
            ])
            ->withCount('questions')
            ->where('section_id', $student->section_id)
            ->where('status', Quiz::STATUS_PUBLISHED);

        if ($selectedAcademicYear !== '') {
            $quizzesQuery->where('academic_year', $selectedAcademicYear);
        }

        $quizzes = $quizzesQuery->latest()->get();

        $attemptsByQuizId = QuizAttempt::query()
            ->where('student_id', $student->id)
            ->whereIn('quiz_id', $quizzes->pluck('id')->all())
            ->get()
            ->keyBy('quiz_id');

        $academicYearOptions = Quiz::query()
            ->where('section_id', $student->section_id)
            ->where('status', Quiz::STATUS_PUBLISHED)
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year');

        return view('pages.students.dashboard.quizzes.index', [
            'student' => $student,
            'quizzes' => $quizzes,
            'attemptsByQuizId' => $attemptsByQuizId,
            'academicYearOptions' => $academicYearOptions,
            'selectedAcademicYear' => $selectedAcademicYear,
        ]);
    }

    public function show(Quiz $quiz): View|RedirectResponse
    {
        $student = $this->authenticatedStudent();
        $quiz = $this->authorizedQuiz($student, $quiz);
        $questionCount = $quiz->questions()->count();

        if ($questionCount === 0) {
            toastr()->error(trans('Quizzes_trans.student_quiz_unavailable_no_questions'));

            return redirect()->route('student.quizzes');
        }

        $maxScore = (int) $quiz->questions()->sum('score');
        $attempt = QuizAttempt::query()->firstOrCreate(
            [
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
            ],
            [
                'status' => QuizAttempt::STATUS_IN_PROGRESS,
                'started_at' => now(),
                'max_score' => $maxScore,
            ]
        );

        if ((int) $attempt->max_score !== $maxScore) {
            $attempt->update(['max_score' => $maxScore]);
            $attempt->refresh();
        }

        return view('pages.students.dashboard.quizzes.show', [
            'student' => $student,
            'quiz' => $quiz,
            'attempt' => $attempt->loadMissing('answers'),
            'questionCount' => $questionCount,
        ]);
    }

    public function results(Request $request): View
    {
        $student = $this->authenticatedStudent();
        $selectedAcademicYear = trim((string) $request->input('academic_year', ''));

        $attemptsQuery = QuizAttempt::query()
            ->with([
                'quiz:id,name,subject_id,teacher_id,academic_year',
                'quiz.subject:id,name',
                'quiz.teacher:id,name',
            ])
            ->where('student_id', $student->id)
            ->whereIn('status', [QuizAttempt::STATUS_SUBMITTED, QuizAttempt::STATUS_BLOCKED]);

        if ($selectedAcademicYear !== '') {
            $attemptsQuery->whereHas('quiz', function ($query) use ($selectedAcademicYear): void {
                $query->where('academic_year', $selectedAcademicYear);
            });
        }

        $attempts = $attemptsQuery->latest('submitted_at')->latest()->get();

        $academicYearOptions = Quiz::query()
            ->where('section_id', $student->section_id)
            ->where('status', Quiz::STATUS_PUBLISHED)
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year');

        return view('pages.students.dashboard.quizzes.results', [
            'student' => $student,
            'attempts' => $attempts,
            'academicYearOptions' => $academicYearOptions,
            'selectedAcademicYear' => $selectedAcademicYear,
        ]);
    }

    private function authenticatedStudent(): Student
    {
        $authenticatedStudent = auth()->guard('student')->user();

        if (! $authenticatedStudent instanceof Student) {
            abort(403);
        }

        return $authenticatedStudent;
    }

    private function authorizedQuiz(Student $student, Quiz $quiz): Quiz
    {
        abort_unless((int) $quiz->section_id === (int) $student->section_id, 403);
        abort_unless($quiz->status === Quiz::STATUS_PUBLISHED, 404);

        return $quiz;
    }
}
