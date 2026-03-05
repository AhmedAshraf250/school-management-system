<?php

namespace App\Http\Requests\Subject;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'grade_id' => ['required', 'integer', 'exists:grades,id'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $classroomId = (int) $this->input('classroom_id');
            $gradeId = (int) $this->input('grade_id');

            if ($classroomId > 0 && $gradeId > 0) {
                $isClassroomBelongsToGrade = Classroom::query()
                    ->whereKey($classroomId)
                    ->where('grade_id', $gradeId)
                    ->exists();

                if (! $isClassroomBelongsToGrade) {
                    $validator->errors()->add('classroom_id', trans('validation.exists'));
                }
            }
        });
    }
}
