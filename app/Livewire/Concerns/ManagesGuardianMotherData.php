<?php

namespace App\Livewire\Concerns;

use App\Models\Guardian;
use Illuminate\Validation\Rule;

trait ManagesGuardianMotherData
{
    public ?string $mother_name = null;

    public ?string $mother_name_en = null;

    public ?string $mother_national_id = null;

    public ?string $mother_passport_id = null;

    public ?string $mother_phone = null;

    public ?string $mother_job = null;

    public ?string $mother_job_en = null;

    public ?int $mother_nationality_id = null;

    public ?int $mother_blood_type_id = null;

    public ?int $mother_religion_id = null;

    public ?string $mother_address = null;

    protected function motherStepRules(?int $guardianId = null): array
    {
        return [
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_name_en' => ['required', 'string', 'max:255'],
            'mother_national_id' => ['required', 'string', 'max:10', 'min:10', Rule::unique('guardians', 'mother_national_id')->ignore($guardianId)],
            'mother_passport_id' => ['required', 'string', 'max:10', 'min:10', Rule::unique('guardians', 'mother_passport_id')->ignore($guardianId)],
            'mother_phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:15', 'min:10'],
            'mother_job' => ['required', 'string', 'max:30'],
            'mother_job_en' => ['required', 'string', 'max:30'],
            'mother_nationality_id' => ['required', 'exists:nationalities,id'],
            'mother_blood_type_id' => ['required', 'exists:blood_types,id'],
            'mother_religion_id' => ['required', 'exists:religions,id'],
            'mother_address' => ['required', 'string', 'max:200'],
        ];
    }

    protected function motherLiveValidationRules(): array
    {
        return [
            'mother_national_id' => 'required|string|min:10|max:10|regex:/^[0-9]{10}$/',
            'mother_passport_id' => 'required|string|min:10|max:10',
            'mother_phone' => 'required|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/|min:10',
        ];
    }

    protected function fillMotherDataIntoGuardian(Guardian $guardian): void
    {
        $guardian->mother_name = ['en' => $this->mother_name_en, 'ar' => $this->mother_name];
        $guardian->mother_national_id = $this->mother_national_id;
        $guardian->mother_passport_id = $this->mother_passport_id;
        $guardian->mother_phone = $this->mother_phone;
        $guardian->mother_job = ['en' => $this->mother_job_en, 'ar' => $this->mother_job];
        $guardian->mother_nationality_id = $this->mother_nationality_id;
        $guardian->mother_blood_type_id = $this->mother_blood_type_id;
        $guardian->mother_religion_id = $this->mother_religion_id;
        $guardian->mother_address = $this->mother_address;
    }

    protected function fillMotherDataFromGuardian(Guardian $guardian): void
    {
        $this->mother_name = $guardian->getTranslation('mother_name', 'ar');
        $this->mother_name_en = $guardian->getTranslation('mother_name', 'en');
        $this->mother_job = $guardian->getTranslation('mother_job', 'ar');
        $this->mother_job_en = $guardian->getTranslation('mother_job', 'en');
        $this->mother_national_id = $guardian->mother_national_id;
        $this->mother_passport_id = $guardian->mother_passport_id;
        $this->mother_phone = $guardian->mother_phone;
        $this->mother_nationality_id = $guardian->mother_nationality_id;
        $this->mother_blood_type_id = $guardian->mother_blood_type_id;
        $this->mother_religion_id = $guardian->mother_religion_id;
        $this->mother_address = $guardian->mother_address;
    }

    protected function resetMotherData(): void
    {
        $this->mother_name = null;
        $this->mother_name_en = null;
        $this->mother_national_id = null;
        $this->mother_passport_id = null;
        $this->mother_phone = null;
        $this->mother_job = null;
        $this->mother_job_en = null;
        $this->mother_nationality_id = null;
        $this->mother_blood_type_id = null;
        $this->mother_religion_id = null;
        $this->mother_address = null;
    }
}
