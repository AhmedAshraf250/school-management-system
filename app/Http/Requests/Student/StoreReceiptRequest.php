<?php

namespace App\Http\Requests\Student;

use App\Models\StudentAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'debit' => 'required|numeric|gt:0',
            'description' => 'required|string|max:1000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $studentId = (int) $this->input('student_id');
            $amount = (float) $this->input('debit');

            $outstandingBalance = $this->outstandingBalance($studentId);

            if ($outstandingBalance <= 0) {
                $validator->errors()->add('debit', trans('fees_trans.no_outstanding_balance'));

                return;
            }

            if ($amount > $outstandingBalance) {
                $validator->errors()->add('debit', trans('fees_trans.amount_exceeds_balance'));
            }
        });
    }

    // ترجع المبلغ المعلق والقائم او المستحق الدفع
    private function outstandingBalance(int $studentId): float
    {
        $debitTotal = (float) StudentAccount::where('student_id', $studentId)->sum('debit');
        $creditTotal = (float) StudentAccount::where('student_id', $studentId)->sum('credit');

        return $debitTotal - $creditTotal;
    }
}
