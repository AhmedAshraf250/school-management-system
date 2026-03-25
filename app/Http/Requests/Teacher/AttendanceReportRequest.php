<?php

namespace App\Http\Requests\Teacher;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AttendanceReportRequest extends FormRequest
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
        /** @var \App\Models\Teacher $teacher */
        $teacher = auth('teacher')->user();
        $teacherSectionIds = $teacher
            ? $teacher->sections()->pluck('sections.id')->all()
            : [];

        return [
            'section_id' => [
                'nullable',
                'integer',
                Rule::exists('sections', 'id')->where(function ($query) use ($teacherSectionIds) {
                    $query->whereIn('id', $teacherSectionIds);
                }),
            ],
            'student_id' => [
                'nullable',
                'integer',
                Rule::exists('students', 'id')->where(function ($query) use ($teacherSectionIds) {
                    $query->whereIn('section_id', $teacherSectionIds);
                }),
            ],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->filled('section_id') && $this->filled('student_id')) {
                $belongsToSection = Student::query()
                    ->whereKey($this->integer('student_id'))
                    ->where('section_id', $this->integer('section_id'))
                    ->exists();

                if (! $belongsToSection) {
                    $validator->errors()->add('student_id', trans('main_trans.teacher_reports_student_section_mismatch'));
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'date_from.required' => trans('main_trans.teacher_reports_date_from_required'),
            'date_to.required' => trans('main_trans.teacher_reports_date_to_required'),
        ];
    }
}
