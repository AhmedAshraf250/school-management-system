<?php

namespace App\Services\Students;

use App\Repositories\Contracts\PromotionRepositoryInterface;
use App\Repositories\Contracts\StaticDataRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PromotionService
{
    public function __construct(
        protected StaticDataRepositoryInterface $staticData,
        protected PromotionRepositoryInterface $promotionRepository
    ) {}

    public function getPromotionHistory(): Collection
    {
        return $this->promotionRepository->getPromotionHistory();
    }

    public function getPromotionFormData(): array
    {
        return [
            'grades' => $this->staticData->getGrades(),
        ];
    }

    public function promote(array $data, ?int $createdBy = null): int
    {
        $this->validatePromotionPath($data);

        $students = $this->promotionRepository->findEligibleStudents($data);

        if ($students->isEmpty()) {
            throw new \DomainException('Students_trans.no_students_for_promotion');
        }

        $promotedCount = 0;

        DB::transaction(function () use ($students, $data, $createdBy, &$promotedCount): void {
            foreach ($students as $student) {
                $isAlreadyPromoted = $this->promotionRepository->hasPromotionForAcademicYear(
                    $student->id,
                    (string) $data['academic_year_to']
                );

                if ($isAlreadyPromoted) {
                    continue;
                }

                $this->promotionRepository->createPromotionRecord($student, $data, $createdBy);
                $this->promotionRepository->updateStudentAfterPromotion($student, $data);
                $promotedCount++;
            }
        });

        if ($promotedCount === 0) {
            throw new \DomainException('Students_trans.promotion_already_exists');
        }

        return $promotedCount;
    }

    public function rollbackOne(int $promotionId): void
    {
        $promotion = $this->promotionRepository->findPromotionWithStudentOrFail($promotionId);

        DB::transaction(function () use ($promotion): void {
            $this->promotionRepository->rollbackStudentFromPromotion($promotion);
            $this->promotionRepository->deletePromotion($promotion);
        });
    }

    public function rollbackAll(): int
    {
        $rolledBackCount = 0;

        DB::transaction(function () use (&$rolledBackCount): void {
            $promotions = $this->promotionRepository->getAllPromotionsWithStudent();

            foreach ($promotions as $promotion) {
                $this->promotionRepository->rollbackStudentFromPromotion($promotion);
                $rolledBackCount++;
            }

            $this->promotionRepository->deleteAllPromotions();
        });

        return $rolledBackCount;
    }

    public function validatePromotionPath(array $data): void
    {
        if (
            (int) $data['from_grade_id'] === (int) $data['to_grade_id']
            && (int) $data['from_classroom_id'] === (int) $data['to_classroom_id']
            && (int) $data['from_section_id'] === (int) $data['to_section_id']
        ) {
            throw new \DomainException('Students_trans.same_promotion_path');
        }
    }
}
