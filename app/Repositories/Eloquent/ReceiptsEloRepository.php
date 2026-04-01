<?php

namespace App\Repositories\Eloquent;

use App\Models\FundAccount;
use App\Models\Receipt;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Repositories\Contracts\ReceiptsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReceiptsEloRepository implements ReceiptsRepositoryInterface
{
    public function all(bool $onlyTrashed = false): Collection
    {
        $query = Receipt::query()
            ->with('student:id,name')
            ->orderByDesc('date');

        if ($onlyTrashed) {
            $query->onlyTrashed();
        }

        return $query->get();
    }

    public function find(int $id): Receipt
    {
        return Receipt::with('student')->findOrFail($id);
    }

    public function getStudentWithAccount(int $studentId): Student
    {
        return Student::with('student_account')->findOrFail($studentId);
    }

    public function createReceipt(array $data): Receipt
    {
        return DB::transaction(function () use ($data) {
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];
            $this->assertAmountCanBeCollected($student->id, $amount);

            $receipt = Receipt::create([
                'date' => now(),
                'student_id' => $student->id,
                'debit' => $amount,
                'description' => $data['description'] ?? '',
            ]);

            $this->syncFundAccount($receipt, $amount);
            $this->syncStudentAccount($receipt, $amount, $student, 'receipt');

            return $receipt;
        });
    }

    public function updateReceipt(int $id, array $data): Receipt
    {
        return DB::transaction(function () use ($id, $data) {
            $receipt = $this->find($id);
            $student = $this->loadStudent($data['student_id']);
            $amount = (float) $data['debit'];
            $this->assertAmountCanBeCollected($student->id, $amount, $receipt);

            $receipt->update([
                'date' => now(),
                'student_id' => $student->id,
                'debit' => $amount,
                'description' => $data['description'] ?? $receipt->description,
            ]);

            $this->syncFundAccount($receipt, $amount, true);
            $this->syncStudentAccount($receipt, $amount, $student, 'receipt', true);

            return $receipt;
        });
    }

    public function deleteReceipt(int $id): void
    {
        Receipt::findOrFail($id)->delete();
    }

    public function restoreReceipt(int $id): void
    {
        Receipt::withTrashed()->findOrFail($id)->restore();
    }

    private function loadStudent(int $studentId): Student
    {
        return Student::findOrFail($studentId);
    }

    private function syncFundAccount(Receipt $receipt, float $amount, bool $update = false): void
    {
        $payload = [
            'date' => now(),
            'receipt_id' => $receipt->id,
            'debit' => $amount,
            'credit' => 0.00,
            'description' => $receipt->description,
        ];

        if ($update) {
            FundAccount::where('receipt_id', $receipt->id)->firstOrFail()->update($payload);
        } else {
            FundAccount::create($payload);
        }
    }

    private function syncStudentAccount(Receipt $receipt, float $amount, Student $student, string $type, bool $update = false): void
    {
        $payload = [
            'date' => now(),
            'type' => $type,
            'student_id' => $student->id,
            'grade_id' => $student->grade_id,
            'classroom_id' => $student->classroom_id,
            'receipt_id' => $receipt->id,
            'debit' => 0.00,
            'credit' => $amount,
            'description' => $receipt->description,
        ];

        if ($update) {
            StudentAccount::where('receipt_id', $receipt->id)->firstOrFail()->update($payload);
        } else {
            StudentAccount::create($payload);
        }
    }

    private function assertAmountCanBeCollected(int $studentId, float $amount, ?Receipt $receipt = null): void
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'debit' => [trans('validation.gt.numeric', ['attribute' => trans('fees_trans.amount'), 'value' => 0])],
            ]);
        }

        $availableBalance = $this->availableBalance($studentId, $receipt);

        if ($availableBalance <= 0) {
            throw ValidationException::withMessages([
                'debit' => [trans('fees_trans.no_outstanding_balance')],
            ]);
        }

        if ($amount > $availableBalance) {
            throw ValidationException::withMessages([
                'debit' => [trans('fees_trans.amount_exceeds_balance')],
            ]);
        }
    }

    private function availableBalance(int $studentId, ?Receipt $receipt = null): float
    {
        $outstandingBalance = $this->outstandingBalance($studentId);

        if (! $receipt instanceof Receipt || $receipt->student_id !== $studentId) {
            return $outstandingBalance;
        }

        $currentReceiptCredit = (float) StudentAccount::query()
            ->includedInTotals()
            ->where('receipt_id', $receipt->id)
            ->value('credit');

        return $outstandingBalance + ($currentReceiptCredit > 0 ? $currentReceiptCredit : (float) $receipt->debit);
    }

    private function outstandingBalance(int $studentId): float
    {
        $debitTotal = (float) StudentAccount::query()
            ->includedInTotals()
            ->where('student_id', $studentId)
            ->sum('debit');
        $creditTotal = (float) StudentAccount::query()
            ->includedInTotals()
            ->where('student_id', $studentId)
            ->sum('credit');

        return $debitTotal - $creditTotal;
    }
}
