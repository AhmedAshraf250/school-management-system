<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
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
        $teacher = $this->route('teacher');
        $teacherId = is_object($teacher) ? $teacher->id : $teacher;

        return [
            'email' => ['required', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($teacherId)],
            'password' => ['nullable', 'string', 'min:8'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'gender_id' => ['required', 'exists:genders,id'],
            'joining_date' => ['required', 'date'],
            'address' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => trans('validation.required'),
            'email.email' => trans('validation.email'),
            'email.unique' => trans('validation.unique'),
            'password.min' => trans('validation.min.string'),
            'name_ar.required' => trans('validation.required'),
            'name_en.required' => trans('validation.required'),
            'specialization_id.required' => trans('validation.required'),
            'specialization_id.exists' => trans('validation.exists'),
            'gender_id.required' => trans('validation.required'),
            'gender_id.exists' => trans('validation.exists'),
            'joining_date.required' => trans('validation.required'),
            'joining_date.date' => trans('validation.date'),
            'address.required' => trans('validation.required'),
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => trans('Teacher_trans.Email'),
            'password' => trans('Teacher_trans.Password'),
            'name_ar' => trans('Teacher_trans.Name_ar'),
            'name_en' => trans('Teacher_trans.Name_en'),
            'specialization_id' => trans('Teacher_trans.specialization'),
            'gender_id' => trans('Teacher_trans.Gender'),
            'joining_date' => trans('Teacher_trans.Joining_Date'),
            'address' => trans('Teacher_trans.Address'),
        ];
    }
}
