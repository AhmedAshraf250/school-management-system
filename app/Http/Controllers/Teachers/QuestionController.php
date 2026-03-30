<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherQuestionRequest;
use App\Http\Requests\Teacher\UpdateTeacherQuestionRequest;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function all(Request $request)
    {
        $teacher = $this->authenticatedTeacher();
        $sectionIds = $teacher->sections()->pluck('sections.id');
        $selectedGradeId = $request->integer('grade_id');
        $selectedClassroomId = $request->integer('classroom_id');
        $selectedSectionId = $request->integer('section_id');
        $selectedAcademicYear = trim((string) $request->input('academic_year', ''));

        $questions = Question::query()
            ->with([
                'quiz:id,name,teacher_id,grade_id,classroom_id,section_id,academic_year',
                'quiz.grade:id,Name',
                'quiz.classroom:id,name',
                'quiz.section:id,name',
            ])
            ->whereHas('quiz', function ($quizQuery) use (
                $teacher,
                $sectionIds,
                $selectedGradeId,
                $selectedClassroomId,
                $selectedSectionId,
                $selectedAcademicYear
            ): void {
                $quizQuery->where('teacher_id', $teacher->id)
                    ->whereIn('section_id', $sectionIds->all());

                if ($selectedGradeId > 0) {
                    $quizQuery->where('grade_id', $selectedGradeId);
                }

                if ($selectedClassroomId > 0) {
                    $quizQuery->where('classroom_id', $selectedClassroomId);
                }

                if ($selectedSectionId > 0) {
                    $quizQuery->where('section_id', $selectedSectionId);
                }

                if ($selectedAcademicYear !== '') {
                    $quizQuery->where('academic_year', $selectedAcademicYear);
                }
            })
            ->latest()
            ->get();

        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name', 'sections.grade_id', 'sections.classroom_id']);

        $gradeOptions = $teacherSections
            ->map(fn (Section $section): array => ['id' => $section->grade_id, 'name' => $section->grade?->Name ?? '-'])
            ->unique('id')
            ->values();

        $classroomOptions = $teacherSections
            ->map(fn (Section $section): array => ['id' => $section->classroom_id, 'name' => $section->classroom?->name ?? '-'])
            ->unique('id')
            ->values();

        $academicYearOptions = Quiz::query()
            ->where('teacher_id', $teacher->id)
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year');

        return view('pages.teachers.dashboard.quizzes.questions.all', [
            'teacher' => $teacher,
            'questions' => $questions,
            'gradeOptions' => $gradeOptions,
            'classroomOptions' => $classroomOptions,
            'sectionOptions' => $teacherSections,
            'academicYearOptions' => $academicYearOptions,
            'selectedGradeId' => $selectedGradeId,
            'selectedClassroomId' => $selectedClassroomId,
            'selectedSectionId' => $selectedSectionId,
            'selectedAcademicYear' => $selectedAcademicYear,
        ]);
    }

    public function index(Quiz $quiz): View
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);

        return view('pages.teachers.dashboard.quizzes.questions.index', [
            'teacher' => $teacher,
            'quiz' => $quiz,
            'questions' => $quiz->questions()->latest()->get(),
        ]);
    }

    public function create(Quiz $quiz): View
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);

        return view('pages.teachers.dashboard.quizzes.questions.create', [
            'teacher' => $teacher,
            'quiz' => $quiz,
        ]);
    }

    public function store(StoreTeacherQuestionRequest $request, Quiz $quiz): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $validatedData = $request->validated();

        $quiz->questions()->create([
            'title' => $validatedData['title'],
            'answers' => $validatedData['answers'],
            'right_answer' => $validatedData['right_answer'],
            'score' => $validatedData['score'],
        ]);

        toastr()->success(trans('messages.success'));

        return redirect()->route('teacher.quizzes.questions.index', $quiz->id);
    }

    public function show(Question $question): RedirectResponse
    {
        abort(404);
    }

    public function edit(Quiz $quiz, Question $question): View
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $question = $this->authorizedQuestion($quiz, $question);

        return view('pages.teachers.dashboard.quizzes.questions.edit', [
            'teacher' => $teacher,
            'quiz' => $quiz,
            'question' => $question,
        ]);
    }

    public function update(UpdateTeacherQuestionRequest $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $question = $this->authorizedQuestion($quiz, $question);
        $validatedData = $request->validated();

        $question->update([
            'title' => $validatedData['title'],
            'answers' => $validatedData['answers'],
            'right_answer' => $validatedData['right_answer'],
            'score' => $validatedData['score'],
        ]);

        toastr()->success(trans('messages.Update'));

        return redirect()->route('teacher.quizzes.questions.index', $quiz->id);
    }

    public function destroy(Quiz $quiz, Question $question): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $question = $this->authorizedQuestion($quiz, $question);
        $question->delete();

        toastr()->success(trans('messages.Delete'));

        return redirect()->route('teacher.quizzes.questions.index', $quiz->id);
    }

    private function authenticatedTeacher(): Teacher
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }

        return $authenticatedTeacher;
    }

    private function authorizedQuiz(Teacher $teacher, Quiz $quiz): Quiz
    {
        abort_unless((int) $quiz->teacher_id === (int) $teacher->id, 403);

        return $quiz;
    }

    private function authorizedQuestion(Quiz $quiz, Question $question): Question
    {
        abort_unless((int) $question->quiz_id === (int) $quiz->id, 404);

        return $question;
    }
}
