<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_grade_id' => ['required', 'integer', 'exists:grades,id'],
            'from_classroom_id' => [
                'required',
                'integer',
                Rule::exists('classrooms', 'id')->where(fn ($query): mixed => $query->where('grade_id', $this->from_grade_id)),
            ],
            'from_section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(fn ($query): mixed => $query
                    ->where('grade_id', $this->from_grade_id)
                    ->where('classroom_id', $this->from_classroom_id)),
            ],
            'academic_year_from' => ['required', 'string', 'max:20'],
            'to_grade_id' => ['required', 'integer', 'exists:grades,id'],
            'to_classroom_id' => [
                'required',
                'integer',
                Rule::exists('classrooms', 'id')->where(fn ($query): mixed => $query->where('grade_id', $this->to_grade_id)),
            ],
            'to_section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(fn ($query): mixed => $query
                    ->where('grade_id', $this->to_grade_id)
                    ->where('classroom_id', $this->to_classroom_id)),
            ],
            'academic_year_to' => ['required', 'string', 'max:20', 'different:academic_year_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'from_grade_id' => trans('Students_trans.from_grade'),
            'from_classroom_id' => trans('Students_trans.from_classroom'),
            'from_section_id' => trans('Students_trans.from_section'),
            'academic_year_from' => trans('Students_trans.academic_year_from'),
            'to_grade_id' => trans('Students_trans.to_grade'),
            'to_classroom_id' => trans('Students_trans.to_classroom'),
            'to_section_id' => trans('Students_trans.to_section'),
            'academic_year_to' => trans('Students_trans.academic_year_to'),
        ];
    }
}
