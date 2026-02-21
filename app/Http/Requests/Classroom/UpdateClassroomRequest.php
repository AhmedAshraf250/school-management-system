<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Name' => [
                'required',
                'string',
                'max:255',
                // Check if name->ar exists in the same grade, excluding current record
                Rule::unique('classrooms', 'name->ar')
                    ->where('grade_id', $this->grade_id)
                    ->ignore($this->id),
            ],
            'Name_en' => [
                'required',
                'string',
                'max:255',
                // Check if name->en exists in the same grade, excluding current record
                Rule::unique('classrooms', 'name->en')
                    ->where('grade_id', $this->grade_id)
                    ->ignore($this->id),
            ],
            'grade_id' => 'required|exists:grades,id',
        ];
    }

    public function messages(): array
    {
        return [
            'Name.unique' => trans('Grades_trans.exists'),
            'Name_class_en.unique' => trans('Grades_trans.exists'),
        ];
    }
}
