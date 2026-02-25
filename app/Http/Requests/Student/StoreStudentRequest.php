<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'gender_id' => ['required', 'integer', 'exists:genders,id'],
            'nationality_id' => ['required', 'integer', 'exists:nationalities,id'],
            'blood_id' => ['required', 'integer', 'exists:blood_types,id'],
            'date_birth' => ['required', 'date'],
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
            'guardian_id' => ['required', 'integer', 'exists:guardians,id'],
            'academic_year' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'classroom_id.exists' => trans('validation.exists'),
            'section_id.exists' => trans('validation.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ar' => trans('Students_trans.name_ar'),
            'name_en' => trans('Students_trans.name_en'),
            'email' => trans('Students_trans.email'),
            'password' => trans('Students_trans.password'),
            'gender_id' => trans('Students_trans.gender'),
            'nationality_id' => trans('Students_trans.Nationality'),
            'blood_id' => trans('Students_trans.blood_type'),
            'date_birth' => trans('Students_trans.Date_of_Birth'),
            'grade_id' => trans('Students_trans.Grade'),
            'classroom_id' => trans('Students_trans.classrooms'),
            'section_id' => trans('Students_trans.section'),
            'guardian_id' => trans('Students_trans.parent'),
            'academic_year' => trans('Students_trans.academic_year'),
        ];
    }
}
