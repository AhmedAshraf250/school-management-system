<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::with([
            'grade:id,Name',
            'classroom:id,name',
            'teacher:id,name',
        ])->latest()->get();

        return view('pages.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $grades = Grade::query()->select('id', 'Name')->get();
        $teachers = Teacher::query()->select('id', 'name')->get();

        return view('pages.subjects.create', compact('grades', 'teachers'));
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            Subject::query()->create([
                'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'teacher_id' => $validated['teacher_id'],
            ]);

            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('subjects.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function show(Subject $subject): RedirectResponse
    {
        return redirect()->route('subjects.edit', $subject);
    }

    public function edit(Subject $subject): View
    {
        $grades = Grade::query()->select('id', 'Name')->get();
        $teachers = Teacher::query()->select('id', 'name')->get();
        $classrooms = Classroom::query()->where('grade_id', $subject->grade_id)->pluck('name', 'id');

        return view('pages.subjects.edit', compact('subject', 'grades', 'teachers', 'classrooms'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $subject->update([
                'name' => ['ar' => $validated['name_ar'], 'en' => $validated['name_en']],
                'grade_id' => $validated['grade_id'],
                'classroom_id' => $validated['classroom_id'],
                'teacher_id' => $validated['teacher_id'],
            ]);

            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('subjects.index');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => trans('messages.error')]);
        }
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();
        $this->flashError(trans('messages.Delete'));

        return redirect()->route('subjects.index');
    }
}
