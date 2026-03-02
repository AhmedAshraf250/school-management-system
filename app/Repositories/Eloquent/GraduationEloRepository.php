<?php

namespace App\Repositories\Eloquent;

use App\Models\Graduation;
use App\Models\Student;
use App\Repositories\Contracts\GraduationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GraduationEloRepository implements GraduationRepositoryInterface
{
    public function getGraduationHistory(): Collection
    {
        return Graduation::query()
            ->with([
                'student:id,name,grade_id,classroom_id,section_id,academic_year,status',
                'student.grade:id,Name',
                'student.classroom:id,name',
                'student.section:id,name',
                'creator:id,name',
            ])
            ->latest('graduated_at')
            ->get();
    }

    public function getActiveStudents(): Collection
    {
        return Student::query()
            ->select(['id', 'name', 'academic_year', 'grade_id', 'classroom_id', 'section_id'])
            ->with([
                'grade:id,Name',
                'classroom:id,name',
                'section:id,name',
            ])
            ->where('status', Student::STATUS_ACTIVE)
            ->latest()
            ->get();
    }

    public function findActiveStudentsByStage(array $data): Collection
    {
        return Student::query()
            ->select(['id', 'status'])
            ->where('grade_id', (int) $data['grade_id'])
            ->where('classroom_id', (int) $data['classroom_id'])
            ->where('section_id', (int) $data['section_id'])
            ->where('academic_year', (string) $data['academic_year'])
            ->where('status', Student::STATUS_ACTIVE)
            ->get();
    }

    public function findStudentOrFail(int $studentId): Student
    {
        return Student::query()->findOrFail($studentId);
    }

    public function createGraduation(array $data): Graduation
    {
        return Graduation::query()->create($data);
    }

    public function markStudentGraduated(Student $student): void
    {
        $student->update([
            'status' => Student::STATUS_GRADUATED,
        ]);
    }

    public function markStudentsGraduatedByIds(array $studentIds): void
    {
        Student::query()
            ->whereIn('id', $studentIds)
            ->update([
                'status' => Student::STATUS_GRADUATED,
            ]);
    }

    public function markStudentActive(Student $student): void
    {
        $student->update([
            'status' => Student::STATUS_ACTIVE,
        ]);
    }

    public function findGraduationWithStudentOrFail(int $graduationId): Graduation
    {
        return Graduation::query()->with('student')->findOrFail($graduationId);
    }

    public function deleteGraduation(Graduation $graduation): void
    {
        $graduation->delete();
    }

    public function createGraduationsBulk(array $rows): void
    {
        Graduation::query()->insert($rows);
    }
}
