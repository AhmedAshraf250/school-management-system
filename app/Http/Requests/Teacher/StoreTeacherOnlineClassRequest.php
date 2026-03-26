<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherOnlineClassRequest extends FormRequest
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
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'topic' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1', 'max:480'],
            'password' => ['nullable', 'string', 'min:6', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.exists' => trans('validation.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'section_id' => trans('Students_trans.section'),
            'topic' => trans('Students_trans.online_class_topic'),
            'start_time' => trans('Students_trans.online_class_start_time'),
            'duration' => trans('Students_trans.online_class_duration'),
            'password' => trans('Students_trans.password'),
        ];
    }
}
