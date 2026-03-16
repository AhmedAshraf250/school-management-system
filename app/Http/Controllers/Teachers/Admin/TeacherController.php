<?php

namespace App\Http\Controllers\Teachers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;

class TeacherController extends Controller
{
    public function __construct(protected TeacherRepositoryInterface $teacherRepository) {}

    public function index()
    {
        $teachers = $this->teacherRepository->getAllTeachers();

        return view('pages.teachers.admin.index', compact('teachers'));
    }

    public function create()
    {
        $specializations = $this->teacherRepository->getSpecializations();
        $genders = $this->teacherRepository->getGenders();

        return view('pages.teachers.admin.create', compact('specializations', 'genders'));
    }

    public function store(StoreTeacherRequest $request)
    {
        try {
            $this->teacherRepository->store($request->validated());
            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function edit(Teacher $teacher)
    {
        $teacher = $this->teacherRepository->edit($teacher->id);
        $specializations = $this->teacherRepository->getSpecializations();
        $genders = $this->teacherRepository->getGenders();

        return view('pages.teachers.admin.edit', compact('teacher', 'specializations', 'genders'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        try {
            $this->teacherRepository->update($request->validated(), $teacher->id);
            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            $this->teacherRepository->delete($teacher->id);
            $this->flashSuccess(trans('messages.Delete'));

            return redirect()->route('teachers.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
