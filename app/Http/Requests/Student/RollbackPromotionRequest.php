<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class RollbackPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => ['nullable', 'integer', 'in:1'],
            'promotion_id' => ['nullable', 'integer', 'exists:promotions,id'],
        ];
    }
}
