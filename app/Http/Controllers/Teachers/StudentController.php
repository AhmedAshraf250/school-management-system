<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\AttendanceReportRequest;
use App\Http\Requests\Teacher\StoreTeacherAttendanceRequest;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $this->authenticatedTeacher();
        $sectionIds = $this->teacherSectionIds($teacher);
        $attendanceDate = $request->input('attendence_date', now()->toDateString());
        $attendanceDay = Carbon::parse($attendanceDate)->startOfDay(); // 18-3-1999 12:23:11 ==> 18-3-1999 00:00:00
        $today = now()->startOfDay();
        $isFutureAttendanceDate = $attendanceDay->gt($today);
        $isLockedPastAttendanceDate = $attendanceDay->lt($today->copy()->subDay());
        $canEditAttendance = ! $isFutureAttendanceDate && ! $isLockedPastAttendanceDate; // يمكنه التعديل فقط لو اليوم : حالى - ليس يوم مستقبلى - يوم واحد فقط ماضى

        $selectedSectionId = $request->integer('section_id');

        $studentsQuery = Student::query()
            ->with(['grade:id,Name', 'classroom:id,name', 'section:id,name'])
            ->with([
                'attendances' => function ($attendanceQuery) use ($teacher, $attendanceDate) {
                    $attendanceQuery->whereDate('attendence_date', $attendanceDate)
                        ->where('teacher_id', $teacher->id);
                },
            ])
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
            'attendanceDate' => $attendanceDate,
            'isFutureAttendanceDate' => $isFutureAttendanceDate,
            'isLockedPastAttendanceDate' => $isLockedPastAttendanceDate,
            'canEditAttendance' => $canEditAttendance,
        ]);
    }

    public function show(Student $student)
    {
        $teacher = $this->authenticatedTeacher();
        $sectionIds = $this->teacherSectionIds($teacher);
        abort_unless($sectionIds->contains($student->section_id), 403);

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

    public function sections()
    {
        $teacher = $this->authenticatedTeacher();

        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->withCount('students')
            ->get();

        return view('pages.teachers.dashboard.sections.index', [
            'teacher' => $teacher,
            'teacherSections' => $teacherSections,
        ]);
    }

    public function storeAttendance(StoreTeacherAttendanceRequest $request)
    {
        $teacher = $this->authenticatedTeacher();
        $sectionIds = $this->teacherSectionIds($teacher);
        $validatedData = $request->validated();

        $students = Student::query()
            ->whereIn('id', $validatedData['student_ids'])
            ->whereIn('section_id', $sectionIds->all())
            ->get(['id', 'grade_id', 'classroom_id', 'section_id']);

        abort_if($students->isEmpty(), 403);

        DB::transaction(function () use ($students, $validatedData, $teacher) {
            foreach ($students as $student) {
                $attendanceValue = $validatedData['attendences'][$student->id] ?? null;

                if ($attendanceValue === null) {
                    continue;
                }

                Attendance::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'attendence_date' => $validatedData['attendence_date'],
                    ],
                    [
                        'grade_id' => $student->grade_id,
                        'classroom_id' => $student->classroom_id,
                        'section_id' => $student->section_id,
                        'attendence_status' => $attendanceValue === 'presence',
                    ],
                );
            }
        });

        toastr()->success(trans('messages.success'));

        return redirect()->route('teacher.students.index', [
            'section_id' => $request->integer('section_id'),
            'attendence_date' => $validatedData['attendence_date'],
        ]);
    }

    public function attendanceReport(AttendanceReportRequest $request)
    {
        $teacher = $this->authenticatedTeacher();
        $sectionIds = $this->teacherSectionIds($teacher);
        $teacherSections = $teacher->sections()
            ->with(['grade:id,Name', 'classroom:id,name'])
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name', 'sections.grade_id', 'sections.classroom_id']);
        $teacherSections->loadCount('students');

        $validatedData = $request->validated();
        $selectedSectionId = ($validatedData['section_id'] ?? 0);
        $selectedStudentId = ($validatedData['student_id'] ?? 0);
        $searchTerm = trim($validatedData['search'] ?? '');
        $dateFrom = $validatedData['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $validatedData['date_to'] ?? now()->toDateString();

        if ($selectedSectionId === 0 && $teacherSections->isNotEmpty()) {
            $selectedSectionId = $teacherSections->first()->id;
        }

        if ($selectedSectionId > 0) {
            abort_unless($sectionIds->contains($selectedSectionId), 403);
        }

        $students = Student::query()
            ->whereIn('section_id', $sectionIds->all())
            ->where('section_id', $selectedSectionId)
            ->orderBy('id')
            ->get(['id', 'name', 'section_id']);

        if ($selectedStudentId > 0) {
            $isTeacherStudent = Student::query()
                ->where('id', $selectedStudentId)
                ->whereIn('section_id', $sectionIds->all())
                ->exists();

            abort_unless($isTeacherStudent, 403);
        }

        $attendancesQuery = Attendance::query()
            ->with([
                'student:id,name,section_id,grade_id,classroom_id',
                'student.grade:id,Name',
                'student.classroom:id,name',
                'section:id,name',
            ])
            ->where('teacher_id', $teacher->id)
            ->whereIn('section_id', $sectionIds->all())
            ->whereBetween('attendence_date', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay(),
            ]);

        if ($selectedSectionId > 0) {
            if ($searchTerm === '') {
                $attendancesQuery->where('section_id', $selectedSectionId);
            }
        }

        if ($selectedStudentId > 0) {
            $attendancesQuery->where('student_id', $selectedStudentId);
        }

        if ($searchTerm !== '') {
            $attendancesQuery->whereHas('student', function ($studentQuery) use ($searchTerm) {
                $studentQuery->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $attendances = $attendancesQuery->latest('attendence_date')->paginate(20)->withQueryString();

        $presentCount = (clone $attendancesQuery)->where('attendence_status', true)->count();
        $absentCount = (clone $attendancesQuery)->where('attendence_status', false)->count();

        return view('pages.teachers.dashboard.reports.attendances', [
            'teacher' => $teacher,
            'teacherSections' => $teacherSections,
            'students' => $students,
            'attendances' => $attendances,
            'selectedSectionId' => $selectedSectionId,
            'selectedStudentId' => $selectedStudentId,
            'searchTerm' => $searchTerm,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
        ]);
    }

    private function authenticatedTeacher()
    {
        $authenticatedTeacher = Auth::guard('teacher')->user();

        if (! $authenticatedTeacher instanceof Teacher) {
            abort(403);
        }

        return $authenticatedTeacher;
    }

    private function teacherSectionIds(Teacher $teacher)
    {
        return $teacher->sections()->pluck('sections.id');
    }
}
