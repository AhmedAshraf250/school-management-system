<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        /** @var Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        abort_unless($teacher instanceof Teacher, 403);

        $sectionIds = $teacher->sections()->pluck('sections.id');

        $selectedSectionId = $request->integer('section_id');

        $studentsQuery = Student::query()
            ->with(['grade:id,Name', 'classroom:id,name', 'section:id,name'])
            ->whereIn('section_id', $sectionIds->all());

        if ($selectedSectionId > 0 && $sectionIds->contains($selectedSectionId)) {
            $studentsQuery->where('section_id', $selectedSectionId);
        }

        $students = $studentsQuery->latest()->paginate(15)->withQueryString();

        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->withCount('students')
            ->get();

        return view('pages.teachers.dashboard.students.index', [
            'teacher' => $teacher,
            'students' => $students,
            'teacherSections' => $teacherSections,
            'selectedSectionId' => $selectedSectionId,
        ]);
    }

    public function show(Student $student)
    {
        /** @var Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        abort_unless($teacher instanceof Teacher, 403);

        $sectionIds = $teacher->sections()->pluck('sections.id');
        abort_unless($sectionIds->contains((int) $student->section_id), 403);

        $student->load([
            'gender:id,name',
            'nationality:id,name',
            'bloodType:id,name',
            'grade:id,Name',
            'classroom:id,name',
            'section:id,name',
            'guardian:id,father_name',
        ]);

        return view('pages.teachers.dashboard.students.show', [
            'teacher' => $teacher,
            'student' => $student,
        ]);
    }
}
