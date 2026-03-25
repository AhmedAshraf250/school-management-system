<?php

namespace App\Http\Requests\Teacher;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendence_date' => [
                'required',
                'date',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $attendanceDate = Carbon::parse((string) $value)->startOfDay();
                    $today = now()->startOfDay();

                    if ($attendanceDate->gt($today)) {
                        $fail(trans('main_trans.teacher_attendance_future_date_not_allowed'));
                    }

                    if ($attendanceDate->lt($today->copy()->subDay())) {
                        $fail(trans('main_trans.teacher_attendance_old_date_not_editable'));
                    }
                },
            ],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'integer', 'exists:students,id'],
            'attendences' => ['required', 'array'],
            'attendences.*' => ['nullable', 'in:presence,absent'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendence_date.required' => trans('main_trans.teacher_attendance_date_required'),
            'student_ids.required' => trans('main_trans.teacher_attendance_students_required'),
            'attendences.required' => trans('main_trans.teacher_attendance_status_required'),
        ];
    }
}
