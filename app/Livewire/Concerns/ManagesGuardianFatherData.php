<?php

namespace App\Livewire\Concerns;

use App\Models\Guardian;
use Illuminate\Validation\Rule;

trait ManagesGuardianFatherData
{
    public ?string $email = null;

    public ?string $password = null;

    public ?string $father_name = null;

    public ?string $father_name_en = null;

    public ?string $father_national_id = null;

    public ?string $father_passport_id = null;

    public ?string $father_phone = null;

    public ?string $father_job = null;

    public ?string $father_job_en = null;

    public ?int $father_nationality_id = null;

    public ?int $father_blood_type_id = null;

    public ?int $father_religion_id = null;

    public ?string $father_address = null;

    protected function fatherStepRules(?int $guardianId = null, bool $isUpdate = false): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('guardians', 'email')->ignore($guardianId)],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_name_en' => ['required', 'string', 'max:255'],
            'father_national_id' => ['required', 'string', 'max:10', 'min:10', Rule::unique('guardians', 'father_national_id')->ignore($guardianId)],
            'father_passport_id' => ['required', 'string', 'max:10', 'min:10', Rule::unique('guardians', 'father_passport_id')->ignore($guardianId)],
            'father_phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:15', 'min:10'],
            'father_job' => ['required', 'string', 'max:30'],
            'father_job_en' => ['required', 'string', 'max:30'],
            'father_nationality_id' => ['required', 'exists:nationalities,id'],
            'father_blood_type_id' => ['required', 'exists:blood_types,id'],
            'father_religion_id' => ['required', 'exists:religions,id'],
            'father_address' => ['required', 'string', 'max:200'],
        ];
    }

    protected function fatherLiveValidationRules(): array
    {
        return [
            'email' => 'required|email',
            'father_national_id' => 'required|string|min:10|max:10|regex:/^[0-9]{10}$/',
            'father_passport_id' => 'required|string|min:10|max:10',
            'father_phone' => 'required|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/|min:10',
        ];
    }

    protected function fillFatherDataIntoGuardian(Guardian $guardian): void
    {
        $guardian->email = $this->email;

        if (! empty($this->password)) {
            $guardian->password = bcrypt($this->password);
        }

        $guardian->father_name = ['en' => $this->father_name_en, 'ar' => $this->father_name];
        $guardian->father_national_id = $this->father_national_id;
        $guardian->father_passport_id = $this->father_passport_id;
        $guardian->father_phone = $this->father_phone;
        $guardian->father_job = ['en' => $this->father_job_en, 'ar' => $this->father_job];
        $guardian->father_nationality_id = $this->father_nationality_id;
        $guardian->father_blood_type_id = $this->father_blood_type_id;
        $guardian->father_religion_id = $this->father_religion_id;
        $guardian->father_address = $this->father_address;
    }

    protected function fillFatherDataFromGuardian(Guardian $guardian): void
    {
        $this->email = $guardian->email;
        $this->password = null;
        $this->father_name = $guardian->getTranslation('father_name', 'ar');
        $this->father_name_en = $guardian->getTranslation('father_name', 'en');
        $this->father_job = $guardian->getTranslation('father_job', 'ar');
        $this->father_job_en = $guardian->getTranslation('father_job', 'en');
        $this->father_national_id = $guardian->father_national_id;
        $this->father_passport_id = $guardian->father_passport_id;
        $this->father_phone = $guardian->father_phone;
        $this->father_nationality_id = $guardian->father_nationality_id;
        $this->father_blood_type_id = $guardian->father_blood_type_id;
        $this->father_religion_id = $guardian->father_religion_id;
        $this->father_address = $guardian->father_address;
    }

    protected function resetFatherData(): void
    {
        $this->email = null;
        $this->password = null;
        $this->father_name = null;
        $this->father_name_en = null;
        $this->father_national_id = null;
        $this->father_passport_id = null;
        $this->father_phone = null;
        $this->father_job = null;
        $this->father_job_en = null;
        $this->father_nationality_id = null;
        $this->father_blood_type_id = null;
        $this->father_religion_id = null;
        $this->father_address = null;
    }
}
