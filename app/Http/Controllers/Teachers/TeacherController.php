<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherController extends Controller
{
    public function __construct(protected TeacherRepositoryInterface $teacherRepository) {}

    public function index(): View
    {
        $teachers = $this->teacherRepository->getAllTeachers();

        return view('pages.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        $specializations = $this->teacherRepository->getSpecializations();
        $genders = $this->teacherRepository->getGenders();

        return view('pages.teachers.create', compact('specializations', 'genders'));
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        try {
            $this->teacherRepository->store($request->validated());
            flash()->success(trans('messages.success'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function edit(Teacher $teacher): View
    {
        $teacher = $this->teacherRepository->edit($teacher->id);
        $specializations = $this->teacherRepository->getSpecializations();
        $genders = $this->teacherRepository->getGenders();

        return view('pages.teachers.edit', compact('teacher', 'specializations', 'genders'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            $this->teacherRepository->update($request->validated(), $teacher->id);
            flash()->success(trans('messages.Update'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        try {
            $this->teacherRepository->delete($teacher->id);
            flash()->success(trans('messages.Delete'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
