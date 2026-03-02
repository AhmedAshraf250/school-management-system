<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Repositories\Contracts\GraduationRepositoryInterface;
use App\Repositories\Contracts\StaticDataRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GraduationService
{
    public function __construct(
        protected GraduationRepositoryInterface $graduationRepository,
        protected StaticDataRepositoryInterface $staticData
    ) {}

    public function getGraduationHistory(): Collection
    {
        return $this->graduationRepository->getGraduationHistory();
    }

    public function getGraduationFormData(): array
    {
        return [
            'students' => $this->graduationRepository->getActiveStudents(),
            'grades' => $this->staticData->getGrades(),
        ];
    }

    public function graduate(array $data, ?int $createdBy = null): void
    {
        $student = $this->graduationRepository->findStudentOrFail((int) $data['student_id']);

        if ((string) $student->status === Student::STATUS_GRADUATED) {
            throw new \DomainException('Students_trans.student_already_graduated');
        }

        DB::transaction(function () use ($student, $data, $createdBy): void {
            $this->graduationRepository->markStudentGraduated($student);

            $this->graduationRepository->createGraduation([
                'student_id' => $student->id,
                'graduated_at' => $data['graduated_at'] ?? now(),
                'academic_year' => (string) $data['academic_year'],
                'notes' => $data['notes'] ?? null,
                'created_by' => $createdBy,
            ]);
        });
    }

    public function rollbackOne(int $graduationId): void
    {
        $graduation = $this->graduationRepository->findGraduationWithStudentOrFail($graduationId);

        DB::transaction(function () use ($graduation): void {
            if ($graduation->student !== null) {
                $this->graduationRepository->markStudentActive($graduation->student);
            }

            $this->graduationRepository->deleteGraduation($graduation);
        });
    }

    public function graduateBatch(array $data, ?int $createdBy = null): int
    {
        $students = $this->graduationRepository->findActiveStudentsByStage($data);

        if ($students->isEmpty()) {
            throw new \DomainException('Students_trans.no_students_for_graduation_batch');
        }

        $studentIds = $students->pluck('id')->all();
        $now = now();
        $graduatedAt = $data['graduated_at'] ?? $now;
        $academicYear = (string) $data['academic_year'];
        $notes = $data['notes'] ?? null;

        $rows = array_map(
            fn (int $studentId): array => [
                'student_id' => $studentId,
                'graduated_at' => $graduatedAt,
                'academic_year' => $academicYear,
                'notes' => $notes,
                'created_by' => $createdBy,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $studentIds
        );

        DB::transaction(function () use ($studentIds, $rows): void {
            $this->graduationRepository->markStudentsGraduatedByIds($studentIds);
            $this->graduationRepository->createGraduationsBulk($rows);
        });

        return count($studentIds);
    }
}
