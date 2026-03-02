<?php

namespace App\Repositories\Contracts;

use App\Models\Graduation;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface GraduationRepositoryInterface
{
    public function getGraduationHistory(): Collection;

    public function getActiveStudents(): Collection;

    public function findActiveStudentsByStage(array $data): Collection;

    public function findStudentOrFail(int $studentId): Student;

    public function createGraduation(array $data): Graduation;

    public function markStudentGraduated(Student $student): void;

    public function markStudentsGraduatedByIds(array $studentIds): void;

    public function markStudentActive(Student $student): void;

    public function findGraduationWithStudentOrFail(int $graduationId): Graduation;

    public function deleteGraduation(Graduation $graduation): void;

    public function createGraduationsBulk(array $rows): void;
}
