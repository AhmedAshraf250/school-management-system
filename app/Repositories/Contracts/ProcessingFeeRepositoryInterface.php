<?php

namespace App\Repositories\Contracts;

use App\Models\ProcessingFee;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface ProcessingFeeRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ProcessingFee;

    public function getStudentWithAccount(int $studentId): Student;

    public function createProcessingFee(array $data): ProcessingFee;

    public function updateProcessingFee(int $id, array $data): ProcessingFee;

    public function deleteProcessingFee(int $id): void;
}
