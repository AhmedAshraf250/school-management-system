<?php

namespace App\Http\Requests\Student;

use App\Models\Fee;
use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreFeeInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'list_fees' => 'required|array|min:1',
            'list_fees.*.student_id' => 'required|integer|exists:students,id',
            'list_fees.*.fee_id' => 'required|integer|exists:fees,id',
            'list_fees.*.description' => 'nullable|string|max:1000',
        ];
    }

    //  يتحقق من عدم تكرار نفس الرسوم لنفس الطالب سواء داخل نفس الطلب او اذا كانت مسجلة مسبقًا في قاعدة البيانات
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $rows = $this->input('list_fees', []);
            $requestPairs = [];

            foreach ($rows as $index => $row) {
                $studentId = (int) ($row['student_id'] ?? 0);
                $feeId = (int) ($row['fee_id'] ?? 0);

                if ($studentId <= 0 || $feeId <= 0) {
                    continue;
                }

                $pairKey = $studentId . '-' . $feeId;
                if (isset($requestPairs[$pairKey])) {
                    $validator->errors()->add(
                        "list_fees.$index.fee_id",
                        trans('fees_trans.duplicate_fee_for_student')
                    );
                }

                $requestPairs[$pairKey] = true;

                // unique
                $alreadyExists = FeeInvoice::query()
                    ->where('student_id', $studentId)
                    ->where('fee_id', $feeId)
                    ->exists();

                if ($alreadyExists) {
                    $validator->errors()->add(
                        "list_fees.$index.fee_id",
                        trans('fees_trans.duplicate_fee_for_student')
                    );
                }

                $student = Student::query()->select(['id', 'grade_id', 'classroom_id'])->find($studentId);
                $fee = Fee::query()->select(['id', 'grade_id', 'classroom_id'])->find($feeId);

                if (! $student || ! $fee) {
                    continue;
                }
                // مرحلة الطالب وصفه لابد وان يكونوا نفس مرحلة وصف الرسوم التابعه لهم
                if ((int) $student->grade_id !== (int) $fee->grade_id || (int) $student->classroom_id !== (int) $fee->classroom_id) {
                    $validator->errors()->add(
                        "list_fees.$index.fee_id",
                        trans('fees_trans.fee_not_allowed_for_student')
                    );
                }
            }
        });
    }
}
