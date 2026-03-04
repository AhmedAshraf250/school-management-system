<?php

namespace App\Repositories\Eloquent;

use App\Models\Fee;
use App\Models\FeeInvoice;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Repositories\Contracts\FeeInvoicesRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FeeInvoicesEloRepository implements FeeInvoicesRepositoryInterface
{
    public function all(): Collection
    {
        return FeeInvoice::with([
            'student:id,name',
            'fee:id,title',
            'grade:id,Name',
            'classroom:id,name',
        ])->orderByDesc('invoice_date')->get();
    }

    public function find(int $id): FeeInvoice
    {
        return FeeInvoice::with('student')->findOrFail($id);
    }

    public function getStudentWithAccount(int $studentId): Student
    {
        return Student::with('student_account')->findOrFail($studentId);
    }

    public function availableFees(int $gradeId, int $classroomId): Collection
    {
        return Fee::query()
            ->select(['id', 'title', 'amount'])
            ->where('grade_id', $gradeId)
            ->where('classroom_id', $classroomId)
            ->get();
    }

    public function createInvoices(array $feeRows): void
    {
        DB::transaction(function () use ($feeRows) {
            foreach ($feeRows as $row) {
                $student = $this->loadStudent($row['student_id']);
                $fee = $this->loadFee((int) $row['fee_id']);
                $amount = (float) $fee->amount;
                $this->ensureFeeBelongsToStudent($student, $fee);
                $this->ensureFeeInvoiceIsUnique($student->id, $fee->id);

                $invoice = FeeInvoice::create([
                    'invoice_date' => now(),
                    'student_id' => $student->id,
                    'grade_id' => $student->grade_id,
                    'classroom_id' => $student->classroom_id,
                    'fee_id' => $fee->id,
                    'amount' => $amount,
                    'description' => $row['description'] ?? '',
                ]);

                StudentAccount::create([
                    'student_id' => $student->id,
                    'date' => now(),
                    'type' => 'invoice',
                    'grade_id' => $student->grade_id,
                    'classroom_id' => $student->classroom_id,
                    'fee_invoice_id' => $invoice->id,
                    'debit' => $amount,
                    'credit' => 0.00,
                    'description' => $invoice->description,
                ]);
            }
        });
    }

    public function updateInvoice(int $id, array $data): FeeInvoice
    {
        return DB::transaction(function () use ($id, $data) {
            $invoice = $this->find($id);
            $student = $this->loadStudent($data['student_id']);
            $fee = $this->loadFee((int) $data['fee_id']);
            $amount = (float) $fee->amount;
            $this->ensureFeeBelongsToStudent($student, $fee);
            $this->ensureFeeInvoiceIsUnique($student->id, $fee->id, $invoice->id);

            $invoice->update([
                'fee_id' => $fee->id,
                'amount' => $amount,
                'description' => $data['description'] ?? $invoice->description,
                'student_id' => $student->id,
                'grade_id' => $student->grade_id,
                'classroom_id' => $student->classroom_id,
            ]);

            $studentAccount = StudentAccount::where('fee_invoice_id', $invoice->id)->firstOrFail();
            $studentAccount->update([
                'student_id' => $student->id,
                'grade_id' => $student->grade_id,
                'classroom_id' => $student->classroom_id,
                'debit' => $amount,
                'credit' => 0.00,
                'type' => 'invoice',
                'description' => $invoice->description,
            ]);

            return $invoice;
        });
    }

    public function deleteInvoice(int $id): void
    {
        FeeInvoice::findOrFail($id)->delete();
    }

    private function loadStudent(int $studentId): Student
    {
        return Student::findOrFail($studentId);
    }

    private function loadFee(int $feeId): Fee
    {
        return Fee::findOrFail($feeId);
    }

    private function ensureFeeInvoiceIsUnique(int $studentId, int $feeId, ?int $exceptInvoiceId = null): void
    {
        $query = FeeInvoice::query()
            ->where('student_id', $studentId)
            ->where('fee_id', $feeId);

        if ($exceptInvoiceId !== null) {
            $query->where('id', '!=', $exceptInvoiceId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'fee_id' => [trans('fees_trans.duplicate_fee_for_student')],
            ]);
        }
    }

    private function ensureFeeBelongsToStudent(Student $student, Fee $fee): void
    {
        if ((int) $fee->grade_id !== (int) $student->grade_id || (int) $fee->classroom_id !== (int) $student->classroom_id) {
            throw ValidationException::withMessages([
                'fee_id' => [trans('fees_trans.fee_not_allowed_for_student')],
            ]);
        }
    }
}
