<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class StoreClassroomRequest extends FormRequest
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
     * * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validate the Arabic name (Must be unique within the same grade_id)
            'List_Classes.*.Name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Extract the index of the current row in the repeater
                    $index = explode('.', $attribute)[1];
                    $gradeId = $this->List_Classes[$index]['grade_id'] ?? null;

                    // Check if name->ar exists for the same grade_id in classrooms table
                    $exists = DB::table('classrooms')
                        ->where('name->ar', $value)
                        ->where('grade_id', $gradeId)
                        ->exists();

                    if ($exists) {
                        $fail(trans('classroom_trans.exists'));
                    }
                },
            ],

            // Validate the English name (Must be unique within the same grade_id)
            'List_Classes.*.Name_class_en' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $gradeId = $this->List_Classes[$index]['grade_id'] ?? null;

                    // Check if name->en exists for the same grade_id in classrooms table
                    $exists = DB::table('classrooms')
                        ->where('name->en', $value)
                        ->where('grade_id', $gradeId)
                        ->exists();

                    if ($exists) {
                        $fail(trans('classroom_trans.exists'));
                    }
                },
            ],

            'List_Classes.*.grade_id' => 'required|exists:grades,id',
        ];
    }

    public function attributes()
    {
        return [
            // 'Name' => trans('classroom_trans.Name_class'),
            // 'Name_class_en' => trans('classroom_trans.Name_class_en'),
            'List_Classes.*.Name' => trans('classroom_trans.Name_class'),
            'List_Classes.*.Name_class_en' => trans('classroom_trans.Name_class_en')
        ];
    }
    /**
     * Custom error messages for translation.
     */
    public function messages(): array
    {
        return [
            'List_Classes.*.Name.required' => trans('validation.required'),
            'List_Classes.*.Name_class_en.required' => trans('validation.required'),
            'List_Classes.*.grade_id.required' => trans('validation.required'),
            'List_Classes.*.grade_id.exists' => trans('validation.exists'),
        ];
    }
}
