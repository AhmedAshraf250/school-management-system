<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var Teacher $teacher */
        $teacher = $this->authenticatedTeacher();

        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->withCount('students')
            ->get();

        $teacherStudentsCount = $this->studentsCountBySections($teacherSections);

        $latestStudents = Student::query()
            ->with(['grade:id,Name', 'classroom:id,name', 'section:id,name'])
            ->whereIn('section_id', $teacherSections->pluck('id')->all())
            ->latest()
            ->limit(8)
            ->get();

        return view('pages.teachers.dashboard.dashboard', [
            'teacher' => $teacher,
            'teacherSections' => $teacherSections,
            'teacherSectionsCount' => $teacherSections->count(),
            'teacherStudentsCount' => $teacherStudentsCount,
            'latestStudents' => $latestStudents,
        ]);
    }

    public function calendar()
    {
        $teacher = $this->authenticatedTeacher();

        return view('pages.teachers.dashboard.calendar', [
            'teacher' => $teacher,
        ]);
    }

    public function profile()
    {
        $teacher = $this->authenticatedTeacher();
        $teacher->load(['gender:id,name', 'specialization:id,name']);
        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name', 'sections.grade_id', 'sections.classroom_id']);

        return view('pages.teachers.dashboard.profile', [
            'teacher' => $teacher,
            'teacherSections' => $teacherSections,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $teacher = $this->authenticatedTeacher();
        $validatedData = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:teacher'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $teacher->update([
            'password' => Hash::make($validatedData['password']),
        ]);

        toastr()->success(trans('messages.success'));

        return redirect()->route('teacher.profile')->with('status', 'password-updated');
    }

    private function studentsCountBySections(Collection $sections)
    {
        if ($sections->isEmpty()) {
            return 0;
        }

        return Student::query()
            ->whereIn('section_id', $sections->pluck('id')->all())
            ->count();
    }

    private function authenticatedTeacher(): Teacher
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }
        // abort_unless($authenticatedTeacher instanceof Teacher, 403);

        return $authenticatedTeacher;
    }
}
