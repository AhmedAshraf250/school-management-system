<?php

namespace App\Repositories\Eloquent;

use App\Models\Promotion;
use App\Models\Student;
use App\Repositories\Contracts\PromotionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PromotionEloRepository implements PromotionRepositoryInterface
{
    public function getPromotionHistory()
    {
        return Promotion::query()
            ->with([
                'student:id,name',
                'fromGrade:id,Name',
                'fromClassroom:id,name',
                'fromSection:id,name',
                'toGrade:id,Name',
                'toClassroom:id,name',
                'toSection:id,name',
            ])
            ->latest('promoted_at')
            ->get();
    }

    public function findEligibleStudents(array $data)
    {
        return Student::query()
            ->where('grade_id', (int) $data['from_grade_id'])
            ->where('classroom_id', (int) $data['from_classroom_id'])
            ->where('section_id', (int) $data['from_section_id'])
            ->where('academic_year', (string) $data['academic_year_from'])
            ->get();
    }

    public function hasPromotionForAcademicYear(int $studentId, string $academicYearTo): bool
    {
        return Promotion::query()
            ->where('student_id', $studentId)
            ->where('academic_year_to', $academicYearTo)
            ->exists();
    }

    public function createPromotionRecord(Student $student, array $data, ?int $createdBy = null): Promotion
    {
        return Promotion::query()->create([
            'student_id' => $student->id,
            'from_grade_id' => (int) $data['from_grade_id'],
            'from_classroom_id' => (int) $data['from_classroom_id'],
            'from_section_id' => (int) $data['from_section_id'],
            'to_grade_id' => (int) $data['to_grade_id'],
            'to_classroom_id' => (int) $data['to_classroom_id'],
            'to_section_id' => (int) $data['to_section_id'],
            'academic_year_from' => (string) $data['academic_year_from'],
            'academic_year_to' => (string) $data['academic_year_to'],
            'promoted_at' => now(),
            'created_by' => $createdBy,
        ]);
    }

    public function updateStudentAfterPromotion(Student $student, array $data): void
    {
        $student->update([
            'grade_id' => (int) $data['to_grade_id'],
            'classroom_id' => (int) $data['to_classroom_id'],
            'section_id' => (int) $data['to_section_id'],
            'academic_year' => (string) $data['academic_year_to'],
        ]);
    }

    public function findPromotionWithStudentOrFail(int $promotionId): Promotion
    {
        return Promotion::query()->with('student')->findOrFail($promotionId);
    }

    public function getAllPromotionsWithStudent()
    {
        return Promotion::query()->with('student')->get();
    }

    public function rollbackStudentFromPromotion(Promotion $promotion): void
    {
        $student = $promotion->student;

        if ($student === null) {
            return;
        }

        $student->update([
            'grade_id' => $promotion->from_grade_id,
            'classroom_id' => $promotion->from_classroom_id,
            'section_id' => $promotion->from_section_id,
            'academic_year' => $promotion->academic_year_from,
        ]);
    }

    public function deletePromotion(Promotion $promotion): void
    {
        $promotion->delete();
    }

    public function deleteAllPromotions(): void
    {
        Promotion::query()->delete();
    }
}
