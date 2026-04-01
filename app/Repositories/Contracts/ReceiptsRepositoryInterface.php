<?php

namespace App\Repositories\Contracts;

use App\Models\Receipt;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface ReceiptsRepositoryInterface
{
    public function all(bool $onlyTrashed = false): Collection;

    public function find(int $id): Receipt;

    public function getStudentWithAccount(int $studentId): Student;

    public function createReceipt(array $data): Receipt;

    public function updateReceipt(int $id, array $data): Receipt;

    public function deleteReceipt(int $id): void;

    public function restoreReceipt(int $id): void;
}
