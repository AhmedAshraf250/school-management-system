<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherQuizRequest;
use App\Http\Requests\Teacher\UpdateTeacherQuizRequest;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $teacher = $this->authenticatedTeacher();

        $quizzes = Quiz::query()
            ->with([
                'subject:id,name',
                'grade:id,Name',
                'classroom:id,name',
                'section:id,name',
            ])
            ->withCount('questions')
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        return view('pages.teachers.dashboard.quizzes.index', [
            'teacher' => $teacher,
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        $teacher = $this->authenticatedTeacher();
        $teacherSections = $this->teacherSections($teacher);

        return view('pages.teachers.dashboard.quizzes.create', [
            'teacher' => $teacher,
            'teacherSections' => $teacherSections,
            'subjects' => Subject::query()
                ->where('teacher_id', $teacher->id)
                ->orderBy('id')
                ->get(['id', 'name', 'grade_id', 'classroom_id']),
        ]);
    }

    public function store(StoreTeacherQuizRequest $request): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $validatedData = $request->validated();
        $section = $this->authorizedSection($teacher, (int) $validatedData['section_id']);
        $subject = $this->authorizedSubject($teacher, $section, (int) $validatedData['subject_id']);

        Quiz::query()->create([
            'name' => ['ar' => $validatedData['name_ar'], 'en' => $validatedData['name_en']],
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'grade_id' => $section->grade_id,
            'classroom_id' => $section->classroom_id,
            'section_id' => $section->id,
            'status' => $validatedData['status'],
            'academic_year' => $validatedData['academic_year'],
        ]);

        toastr()->success(trans('messages.success'));

        return redirect()->route('teacher.quizzes.index');
    }

    public function show(Quiz $quiz): RedirectResponse
    {
        return redirect()->route('teacher.quizzes.questions.index', $quiz->id);
    }

    public function edit(Quiz $quiz): View
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);

        return view('pages.teachers.dashboard.quizzes.edit', [
            'teacher' => $teacher,
            'quiz' => $quiz,
            'teacherSections' => $this->teacherSections($teacher),
            'subjects' => Subject::query()
                ->where('teacher_id', $teacher->id)
                ->orderBy('id')
                ->get(['id', 'name', 'grade_id', 'classroom_id']),
        ]);
    }

    public function update(UpdateTeacherQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $validatedData = $request->validated();
        $section = $this->authorizedSection($teacher, (int) $validatedData['section_id']);
        $subject = $this->authorizedSubject($teacher, $section, (int) $validatedData['subject_id']);

        $quiz->update([
            'name' => ['ar' => $validatedData['name_ar'], 'en' => $validatedData['name_en']],
            'subject_id' => $subject->id,
            'grade_id' => $section->grade_id,
            'classroom_id' => $section->classroom_id,
            'section_id' => $section->id,
            'status' => $validatedData['status'],
            'academic_year' => $validatedData['academic_year'],
        ]);

        toastr()->success(trans('messages.Update'));

        return redirect()->route('teacher.quizzes.index');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);
        $quiz->delete();

        toastr()->error(trans('messages.Delete'));

        return redirect()->route('teacher.quizzes.index');
    }

    public function publish(Quiz $quiz): RedirectResponse
    {
        $teacher = $this->authenticatedTeacher();
        $quiz = $this->authorizedQuiz($teacher, $quiz);

        if (! $quiz->questions()->exists()) {
            return redirect()->route('teacher.quizzes.index')
                ->withErrors(['error' => trans('Quizzes_trans.publish_requires_question')]);
        }

        $quiz->update(['status' => Quiz::STATUS_PUBLISHED]);
        toastr()->success(trans('messages.success'));

        return redirect()->route('teacher.quizzes.index');
    }

    private function authenticatedTeacher(): Teacher
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }

        return $authenticatedTeacher;
    }

    private function teacherSections(Teacher $teacher): Collection
    {
        return $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->withCount('students')
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name', 'sections.grade_id', 'sections.classroom_id']);
    }

    private function authorizedQuiz(Teacher $teacher, Quiz $quiz): Quiz
    {
        abort_unless((int) $quiz->teacher_id === (int) $teacher->id, 403);

        return $quiz;
    }

    private function authorizedSection(Teacher $teacher, int $sectionId): Section
    {
        return $teacher->sections()
            ->where('sections.id', $sectionId)
            ->firstOrFail(['sections.id', 'sections.grade_id', 'sections.classroom_id']);
    }

    private function authorizedSubject(Teacher $teacher, Section $section, int $subjectId): Subject
    {
        $subject = Subject::query()
            ->whereKey($subjectId)
            ->where('teacher_id', $teacher->id)
            ->where('grade_id', $section->grade_id)
            ->where('classroom_id', $section->classroom_id)
            ->first();

        if (! $subject instanceof Subject) {
            throw ValidationException::withMessages([
                'subject_id' => trans('Quizzes_trans.subject_not_compatible_with_section'),
            ]);
        }

        return $subject;
    }
}
