<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLibraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
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
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
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
            'title' => trans('libraries_trans.book_title'),
            'grade_id' => trans('Students_trans.Grade'),
            'classroom_id' => trans('Students_trans.classrooms'),
            'section_id' => trans('Students_trans.section'),
            'teacher_id' => trans('libraries_trans.teacher_name'),
            'file' => trans('libraries_trans.attachment'),
        ];
    }
}
