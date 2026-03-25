<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTeacherQuestionRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'answers' => ['required', 'string'],
            'right_answer' => ['required', 'string', 'max:255'],
            'score' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $answers = collect(preg_split('/[\n,]+/', (string) $this->input('answers')))
                ->map(fn (?string $answer): string => trim((string) $answer))
                ->filter()
                ->values();

            $rightAnswer = trim((string) $this->input('right_answer'));

            if ($answers->isNotEmpty() && ! $answers->contains($rightAnswer)) {
                $validator->errors()->add('right_answer', trans('validation.in'));
            }
        });
    }
}
