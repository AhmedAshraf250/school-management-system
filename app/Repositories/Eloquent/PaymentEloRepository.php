<?php

namespace App\Repositories\Eloquent;

use App\Models\FundAccount;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PaymentEloRepository implements PaymentRepositoryInterface
{
    public function all(bool $onlyTrashed = false): Collection
    {
        $query = Payment::query()
            ->with('student:id,name')
            ->orderByDesc('date');

        if ($onlyTrashed) {
            $query->onlyTrashed();
        }

        return $query->get();
    }

    public function find(int $id): Payment
    {
        return Payment::with('student')->findOrFail($id);
    }

    public function getStudentWithAccount(int $studentId): Student
    {
        return Student::with('student_account')->findOrFail($studentId);
    }

    public function createPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];

            $payment = Payment::create([
                'date' => now(),
                'student_id' => $student->id,
                'amount' => $amount,
                'description' => $data['description'] ?? '',
            ]);

            $this->syncFundAccount($payment, $amount);
            $this->syncStudentAccount($payment, $amount, $student, 'payment');

            return $payment;
        });
    }

    public function updatePayment(int $id, array $data): Payment
    {
        return DB::transaction(function () use ($id, $data) {
            $payment = $this->find($id);
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];

            $payment->update([
                'date' => now(),
                'student_id' => $student->id,
                'amount' => $amount,
                'description' => $data['description'] ?? $payment->description,
            ]);

            $this->syncFundAccount($payment, $amount, true);
            $this->syncStudentAccount($payment, $amount, $student, 'payment', true);

            return $payment;
        });
    }

    public function deletePayment(int $id): void
    {
        Payment::findOrFail($id)->delete();
    }

    public function restorePayment(int $id): void
    {
        Payment::withTrashed()->findOrFail($id)->restore();
    }

    private function loadStudent(int $studentId): Student
    {
        return Student::findOrFail($studentId);
    }

    private function syncFundAccount(Payment $payment, float $amount, bool $update = false): void
    {
        $payload = [
            'date' => now(),
            'payment_id' => $payment->id,
            'debit' => 0.00,
            'credit' => $amount,
            'description' => $payment->description,
        ];

        if ($update) {
            FundAccount::where('payment_id', $payment->id)->firstOrFail()->update($payload);
        } else {
            FundAccount::create($payload);
        }
    }

    private function syncStudentAccount(Payment $payment, float $amount, Student $student, string $type, bool $update = false): void
    {
        $payload = [
            'date' => now(),
            'type' => $type,
            'student_id' => $student->id,
            'grade_id' => $student->grade_id,
            'classroom_id' => $student->classroom_id,
            'payment_id' => $payment->id,
            'debit' => $amount,
            'credit' => 0.00,
            'description' => $payment->description,
        ];

        if ($update) {
            StudentAccount::where('payment_id', $payment->id)->firstOrFail()->update($payload);
        } else {
            StudentAccount::create($payload);
        }
    }
}
