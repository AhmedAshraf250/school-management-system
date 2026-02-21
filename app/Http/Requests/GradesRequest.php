<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradesRequest extends FormRequest
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
    public function rules()
    {
        return [
            'Name' => [
                'required',
                'string',
                Rule::unique('grades', 'Name->ar')->ignore($this->id),
            ],
            'Name_en' => [
                'required',
                'string',
                Rule::unique('grades', 'Name->en')->ignore($this->id),
            ],
            'Notes' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'Name' => trans('Grades_trans.Name'),
            'Name_en' => trans('Grades_trans.Name_en'),
        ];
    }

    public function messages()
    {
        return [
            'Name.required' => trans('validation.required'),
            'Name.string' => trans('validation.string'),
            'Name.unique' => trans('Grades_trans.exists'),
            'Name_en.unique' => trans('Grades_trans.exists'),
            'Notes.string' => trans('validation.string'),
        ];
    }
}
