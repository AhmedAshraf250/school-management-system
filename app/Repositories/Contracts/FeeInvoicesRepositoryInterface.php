<?php

namespace App\Repositories\Contracts;

use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface FeeInvoicesRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): FeeInvoice;

    public function getStudentWithAccount(int $studentId): Student;

    public function availableFees(int $gradeId, int $classroomId): Collection;

    public function createInvoices(array $feeRows): void;

    public function updateInvoice(int $id, array $data): FeeInvoice;

    public function deleteInvoice(int $id): void;
}
