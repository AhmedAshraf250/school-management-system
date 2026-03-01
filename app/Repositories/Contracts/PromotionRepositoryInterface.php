<?php

namespace App\Repositories\Contracts;

use App\Models\Promotion;
use App\Models\Student;

interface PromotionRepositoryInterface
{
    public function getPromotionHistory();

    public function findEligibleStudents(array $data);

    public function hasPromotionForAcademicYear(int $studentId, string $academicYearTo): bool;

    public function createPromotionRecord(Student $student, array $data, ?int $createdBy = null): Promotion;

    public function updateStudentAfterPromotion(Student $student, array $data): void;

    public function findPromotionWithStudentOrFail(int $promotionId): Promotion;

    public function getAllPromotionsWithStudent();

    public function rollbackStudentFromPromotion(Promotion $promotion): void;

    public function deletePromotion(Promotion $promotion): void;

    public function deleteAllPromotions(): void;
}
