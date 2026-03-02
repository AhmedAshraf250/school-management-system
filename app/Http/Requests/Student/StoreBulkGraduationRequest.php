<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBulkGraduationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grade_id' => ['required', 'integer', 'exists:grades,id'],
            'classroom_id' => [
                'required',
                'integer',
                Rule::exists('classrooms', 'id')->where(fn ($query): mixed => $query->where('grade_id', $this->grade_id)),
            ],
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(fn ($query): mixed => $query
                    ->where('grade_id', $this->grade_id)
                    ->where('classroom_id', $this->classroom_id)),
            ],
            'academic_year' => ['required', 'string', 'max:20'],
            'graduated_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'grade_id' => trans('Students_trans.Grade'),
            'classroom_id' => trans('Students_trans.classrooms'),
            'section_id' => trans('Students_trans.section'),
            'academic_year' => trans('Students_trans.academic_year'),
            'graduated_at' => trans('Students_trans.graduated_at'),
            'notes' => trans('Students_trans.notes'),
        ];
    }
}
