<?php

namespace App\Http\Requests\Quiz;

use App\Models\Classroom;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateQuizRequest extends FormRequest
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
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'grade_id' => ['required', 'integer', 'exists:grades,id'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $gradeId = (int) $this->input('grade_id');
            $classroomId = (int) $this->input('classroom_id');
            $sectionId = (int) $this->input('section_id');
            $subjectId = (int) $this->input('subject_id');

            if ($classroomId > 0 && $gradeId > 0) {
                $isClassroomBelongsToGrade = Classroom::query()
                    ->whereKey($classroomId)
                    ->where('grade_id', $gradeId)
                    ->exists();

                if (! $isClassroomBelongsToGrade) {
                    $validator->errors()->add('classroom_id', trans('validation.exists'));
                }
            }

            if ($sectionId > 0 && $classroomId > 0) {
                $isSectionBelongsToClassroom = Section::query()
                    ->whereKey($sectionId)
                    ->where('classroom_id', $classroomId)
                    ->exists();

                if (! $isSectionBelongsToClassroom) {
                    $validator->errors()->add('section_id', trans('validation.exists'));
                }
            }

            if ($subjectId > 0 && $gradeId > 0 && $classroomId > 0) {
                $isSubjectBelongsToPath = Subject::query()
                    ->whereKey($subjectId)
                    ->where('grade_id', $gradeId)
                    ->where('classroom_id', $classroomId)
                    ->exists();

                if (! $isSubjectBelongsToPath) {
                    $validator->errors()->add('subject_id', trans('validation.exists'));
                }
            }
        });
    }
}
