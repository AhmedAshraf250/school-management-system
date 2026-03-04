<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Payment;

    public function getStudentWithAccount(int $studentId): Student;

    public function createPayment(array $data): Payment;

    public function updatePayment(int $id, array $data): Payment;

    public function deletePayment(int $id): void;
}
