<?php

namespace App\Http\Requests\Student;

use App\Models\Receipt;
use App\Models\StudentAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateReceiptRequest extends FormRequest
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

    // بتأكد هنا ان هل الطالب عليه مبلغ مستحق ام لا و الملبغ لا يكون اكبر من الكمية المستحقة
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $studentId = (int) $this->input('student_id');
            $amount = (float) $this->input('debit');
            $receipt = $this->route('receipt');

            $receiptModel = $receipt instanceof Receipt ? $receipt : null;
            $availableBalance = $this->availableBalanceForUpdate($studentId, $receiptModel);

            if ($availableBalance <= 0) {
                $validator->errors()->add('debit', trans('fees_trans.no_outstanding_balance'));

                return;
            }

            if ($amount > $availableBalance) {
                $validator->errors()->add('debit', trans('fees_trans.amount_exceeds_balance'));
            }
        });
    }

    private function availableBalanceForUpdate(int $studentId, ?Receipt $receipt): float
    {
        $outstandingBalance = $this->outstandingBalance($studentId);

        if (! $receipt instanceof Receipt || $receipt->student_id !== $studentId) {
            return $outstandingBalance;
        }

        $currentReceiptCredit = (float) StudentAccount::where('receipt_id', $receipt->id)->value('credit');

        return $outstandingBalance + ($currentReceiptCredit > 0 ? $currentReceiptCredit : (float) $receipt->debit);
    }

    // ترجع المبلغ المعلق والقائم او المستحق الدفع
    private function outstandingBalance(int $studentId): float
    {
        $debitTotal = (float) StudentAccount::where('student_id', $studentId)->sum('debit');
        $creditTotal = (float) StudentAccount::where('student_id', $studentId)->sum('credit');

        return $debitTotal - $creditTotal;
    }
}
