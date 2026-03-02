<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreGraduationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'graduated_at' => ['nullable', 'date'],
            'academic_year' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id' => trans('Students_trans.name'),
            'graduated_at' => trans('Students_trans.graduated_at'),
            'academic_year' => trans('Students_trans.academic_year'),
            'notes' => trans('Students_trans.notes'),
        ];
    }
}
