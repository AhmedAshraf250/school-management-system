<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherIndirectOnlineClassRequest extends FormRequest
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
            'meeting_id' => ['required', 'string', 'max:50'],
            'topic' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1', 'max:480'],
            'password' => ['required', 'string', 'min:6', 'max:20'],
            'start_url' => ['required', 'url', 'max:2000'],
            'join_url' => ['required', 'url', 'max:2000'],
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
            'meeting_id' => trans('Students_trans.online_class_meeting_id'),
            'topic' => trans('Students_trans.online_class_topic'),
            'start_time' => trans('Students_trans.online_class_start_time'),
            'duration' => trans('Students_trans.online_class_duration'),
            'password' => trans('Students_trans.password'),
            'start_url' => trans('Students_trans.online_class_start_url'),
            'join_url' => trans('Students_trans.online_class_join_url'),
        ];
    }
}
