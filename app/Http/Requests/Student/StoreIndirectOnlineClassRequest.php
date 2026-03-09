<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIndirectOnlineClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for manual (non-API) class creation.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
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
            'classroom_id.exists' => trans('validation.exists'),
            'section_id.exists' => trans('validation.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'grade_id' => trans('Students_trans.Grade'),
            'classroom_id' => trans('Students_trans.classrooms'),
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
