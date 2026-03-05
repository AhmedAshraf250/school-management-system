<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::with([
            'subject:id,name',
            'teacher:id,name',
            'grade:id,Name',
            'classroom:id,name',
            'section:id,name',
        ])->latest()->get();

        return view('pages.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        $subjects = Subject::query()->select('id', 'name')->get();
        $teachers = Teacher::query()->select('id', 'name')->get();
        $grades = Grade::query()->select('id', 'Name')->get();

        return view('pages.quizzes.create', compact('subjects', 'teachers', 'grades'));
    }

    public function store(StoreQuizRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            Quiz::query()->create([
                'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'section_id' => $validated['section_id'],
            ]);

            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('quizzes.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function show(Quiz $quiz): RedirectResponse
    {
        return redirect()->route('quizzes.edit', $quiz);
    }

    public function edit(Quiz $quiz): View
    {
        $subjects = Subject::query()->select('id', 'name')->get();
        $teachers = Teacher::query()->select('id', 'name')->get();
        $grades = Grade::query()->select('id', 'Name')->get();
        $classrooms = Classroom::query()->where('grade_id', $quiz->grade_id)->pluck('name', 'id');
        $sections = Section::query()->where('classroom_id', $quiz->classroom_id)->pluck('name', 'id');

        return view('pages.quizzes.edit', compact('quiz', 'subjects', 'teachers', 'grades', 'classrooms', 'sections'));
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $quiz->update([
                'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'section_id' => $validated['section_id'],
            ]);

            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('quizzes.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();
        $this->flashError(trans('messages.Delete'));

        return redirect()->route('quizzes.index');
    }
}
