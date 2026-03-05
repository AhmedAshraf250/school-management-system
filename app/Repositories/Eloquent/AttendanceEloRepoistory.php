<?php

namespace App\Repositories\Eloquent;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\Contracts\AttendanceRepoistoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceEloRepoistory implements AttendanceRepoistoryInterface
{
    public function allSections(): Collection
    {
        return Grade::with(['sections.classroom'])->orderByDesc('id')->get();
    }

    public function sectionWithStudents(int $sectionId): Section
    {
        return Section::with([
            'teachers:id',
            'students.gender:id,name',
            'students.grade:id,Name',
            'students.classroom:id,name',
            'students.section:id,name',
            'students.todayAttendance',
        ])->findOrFail($sectionId);
    }

    public function storeAttendance(array $data): void
    {
        DB::transaction(function () use ($data): void {
            $students = Student::query()
                ->whereIn('id', $data['student_ids'])
                ->where('section_id', $data['section_id'])
                ->get(['id', 'grade_id', 'classroom_id', 'section_id']);

            foreach ($students as $student) {
                $attendanceValue = $data['attendences'][$student->id] ?? null;

                if ($attendanceValue === null) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'attendence_date' => $data['attendence_date'],
                    ],
                    [
                        'grade_id' => $student->grade_id,
                        'classroom_id' => $student->classroom_id,
                        'section_id' => $student->section_id,
                        'teacher_id' => $data['teacher_id'],
                        'attendence_status' => $attendanceValue === 'presence',
                    ],
                );
            }
        });
    }
}
