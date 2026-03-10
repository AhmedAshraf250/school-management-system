<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string', 'max:255'],
            'current_session' => ['required', 'string', 'max:20'],
            'school_title' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'end_first_term' => ['nullable', 'date'],
            'end_second_term' => ['nullable', 'date'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_name' => trans('settings_trans.school_name'),
            'current_session' => trans('settings_trans.current_session'),
            'school_title' => trans('settings_trans.school_title'),
            'phone' => trans('settings_trans.phone'),
            'school_email' => trans('settings_trans.school_email'),
            'address' => trans('settings_trans.address'),
            'end_first_term' => trans('settings_trans.end_first_term'),
            'end_second_term' => trans('settings_trans.end_second_term'),
            'logo' => trans('settings_trans.logo'),
        ];
    }
}
