<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    public function __construct(protected StudentRepositoryInterface $studentRepository) {}

    public function index(): View
    {
        $students = $this->studentRepository->getAllStudents();

        return view('pages.students.index', compact('students'));
    }

    public function create(): View
    {
        return view('pages.students.add-student', $this->studentFormData());
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            $this->studentRepository->store($request->validated());
            $this->flashSuccess(trans('messages.success'));

            return redirect()->route('students.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function edit(Student $student): View
    {
        $student = $this->studentRepository->edit($student->id);
        $formData = $this->studentFormData($student);

        return view('pages.students.edit', array_merge($formData, ['student' => $student]));
    }

    public function show(Student $student): RedirectResponse
    {
        return redirect()->route('students.edit', $student->id);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        try {
            $this->studentRepository->update($request->validated(), $student->id);
            $this->flashSuccess(trans('messages.Update'));

            return redirect()->route('students.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(Student $student): RedirectResponse
    {
        try {
            $this->studentRepository->delete($student->id);
            $this->flashSuccess(trans('messages.Delete'));

            return redirect()->route('students.index');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function getClassrooms(int $id): JsonResponse
    {
        $classrooms = $this->studentRepository->getClassroomsByGrade($id);

        return response()->json($classrooms);
    }

    public function getSections(int $id): JsonResponse
    {
        $sections = $this->studentRepository->getSectionsByClassroom($id);

        return response()->json($sections);
    }

    /**
     * Centralized dropdown data for create/edit forms.
     */
    private function studentFormData(?Student $student = null): array
    {
        $grades = $this->studentRepository->getGrades();
        $guardians = $this->studentRepository->getGuardians();
        $genders = $this->studentRepository->getGenders();
        $nationalities = $this->studentRepository->getNationalities();
        $bloodTypes = $this->studentRepository->getBloodTypes();
        $classrooms = collect();
        $sections = collect();

        if ($student !== null) {
            $classrooms = $this->studentRepository->getClassroomsByGrade((int) $student->grade_id);
            $sections = $this->studentRepository->getSectionsByClassroom((int) $student->classroom_id);
        }

        return compact(
            'grades',
            'guardians',
            'genders',
            'nationalities',
            'bloodTypes',
            'classrooms',
            'sections',
        );
    }
}
