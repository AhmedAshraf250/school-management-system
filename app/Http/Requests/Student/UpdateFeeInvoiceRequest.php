<?php

namespace App\Http\Requests\Student;

use App\Models\Fee;
use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateFeeInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'fee_id' => 'required|integer|exists:fees,id',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $studentId = (int) $this->input('student_id');
            $feeId = (int) $this->input('fee_id');
            $invoice = $this->route('fee_invoice');
            $invoiceId = $invoice instanceof FeeInvoice ? $invoice->id : (int) $this->route('fee_invoice');

            if ($studentId <= 0 || $feeId <= 0) {
                return;
            }

            $alreadyExists = FeeInvoice::query()
                ->where('student_id', $studentId)
                ->where('fee_id', $feeId)
                ->where('id', '!=', $invoiceId)
                ->exists();

            if ($alreadyExists) {
                $validator->errors()->add('fee_id', trans('fees_trans.duplicate_fee_for_student'));
            }

            $student = Student::query()->select(['id', 'grade_id', 'classroom_id'])->find($studentId);
            $fee = Fee::query()->select(['id', 'grade_id', 'classroom_id'])->find($feeId);

            if (! $student || ! $fee) {
                return;
            }

            if ((int) $student->grade_id !== (int) $fee->grade_id || (int) $student->classroom_id !== (int) $fee->classroom_id) {
                $validator->errors()->add('fee_id', trans('fees_trans.fee_not_allowed_for_student'));
            }
        });
    }
}
