<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Repositories\Contracts\StaticDataRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function __construct(
        protected StudentRepositoryInterface $studentRepository,
        protected StaticDataRepositoryInterface $staticData
    ) {}

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
        $student = $this->studentRepository->getById($student->id);
        $formData = $this->studentFormData($student);

        return view('pages.students.edit', array_merge($formData, ['student' => $student]));
    }

    public function show(Student $student): View
    {
        $student = $this->studentRepository->getById($student->id);

        return view('pages.students.show', ['student' => $student]);
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
        $grades = $this->staticData->getGrades();
        $guardians = $this->staticData->getGuardians();
        $genders = $this->staticData->getGenders();
        $nationalities = $this->staticData->getNationalities();
        $bloodTypes = $this->staticData->getBloodTypes();
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

    public function uploadAttachments(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => ['required', 'array'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $this->studentRepository->uploadImages($validated['photos'], $student);

        $this->flashSuccess(trans('messages.success'));

        return redirect()->route('students.show', $student->id);
    }

    public function deleteAttachment(Student $student, int $attachmentId): RedirectResponse
    {
        $this->studentRepository->deleteStudentAttachment($student, $attachmentId);

        $this->flashSuccess(trans('messages.success'));

        return redirect()->route('students.show', $student->id);
    }

    public function downloadAttachment(Student $student, int $attachmentId)
    {
        $attachment = $this->studentRepository->getStudentAttachment($student, $attachmentId);

        // return Storage::disk('public')->download($attachment->path, $attachment->file_name);

        $disk = Storage::disk('public');
        return response()->download($disk->path($attachment->path), $attachment->file_name);
    }
}
