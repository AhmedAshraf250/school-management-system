<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'amount' => 'required|numeric',
            'grade_id' => 'required|integer|exists:grades,id',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'description' => 'string|nullable',
            'year' => 'required',
            'type' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'title_ar.required' => trans('validation.required'),
            'title_en.required' => trans('validation.unique'),
            'Password.required' => trans('validation.required'),
            'amount.required' => trans('validation.required'),
            'amount.numeric' => trans('validation.numeric'),
            'grade_id.required' => trans('validation.required'),
            'classroom_id.required' => trans('validation.required'),
            'year.required' => trans('validation.required'),
        ];
    }
}
