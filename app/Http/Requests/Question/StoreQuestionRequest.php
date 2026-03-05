<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'answers' => ['required', 'string'],
            'right_answer' => ['required', 'string', 'max:255'],
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
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
