<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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

    private function studentsCountBySections(Collection $sections)
    {
        if ($sections->isEmpty()) {
            return 0;
        }

        return Student::query()
            ->whereIn('section_id', $sections->pluck('id')->all())
            ->count();
    }

    private function authenticatedTeacher()
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();
        abort_unless($authenticatedTeacher instanceof Teacher, 403);

        return $authenticatedTeacher;
    }
}
