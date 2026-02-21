<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionRequest extends FormRequest
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
        $section = $this->route('section');
        $sectionId = is_object($section) ? $section->id : $section;

        return [
            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections', 'name->ar')
                    ->where(fn ($query) => $query
                        ->where('grade_id', $this->grade_id)
                        ->where('classroom_id', $this->classroom_id))
                    ->ignore($sectionId),
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections', 'name->en')
                    ->where(fn ($query) => $query
                        ->where('grade_id', $this->grade_id)
                        ->where('classroom_id', $this->classroom_id))
                    ->ignore($sectionId),
            ],
            'grade_id' => ['required', 'exists:grades,id'],
            'classroom_id' => [
                'required',
                Rule::exists('classrooms', 'id')->where(fn ($query) => $query->where('grade_id', $this->grade_id)),
            ],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => trans('Sections_trans.required_ar'),
            'name_en.required' => trans('Sections_trans.required_en'),
            'name_ar.unique' => trans('validation.unique'),
            'name_en.unique' => trans('validation.unique'),
            'grade_id.required' => trans('Sections_trans.Grade_id_required'),
            'classroom_id.required' => trans('Sections_trans.Class_id_required'),
            'classroom_id.exists' => trans('Sections_trans.Class_id_required'),
        ];
    }
}
