<?php

namespace App\Livewire;

use App\Models\BloodType;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddGuardian extends Component
{
    public int $currentStep = 1;

    public $successMessage = '';

    public $catchError;

    // Father inputs

    // #[Validate(
    //     [
    //         'email' => 'required',
    //         'email.*' => 'required|min:5',
    //     ],
    //     as: trans('Parent_trans.Email'),
    //     message: [
    //         'email' => 'The :attribute is missing.',
    //         'email.required' => 'The :attribute are missing.',
    //         'min' => 'The :attribute is too short.',
    //     ],
    //     attribute: [
    //         'email.*' => 'email',
    //     ]
    // )]
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

    public ?string $father_address = null;

    public ?int $father_religion_id = null;

    // Mother inputs
    public ?string $mother_name = null;

    public ?string $mother_name_en = null;

    public ?string $mother_national_id = null;

    public ?string $mother_passport_id = null;

    public ?string $mother_phone = null;

    public ?string $mother_job = null;

    public ?string $mother_job_en = null;

    public ?int $mother_nationality_id = null;

    public ?int $mother_blood_type_id = null;

    public ?string $mother_address = null;

    public ?int $mother_religion_id = null;

    public function render(): View
    {
        return view('livewire.add-guardian', [
            'nationalities' => Nationality::query()->get(),
            'blood_types' => BloodType::query()->get(),
            'religions' => Religion::query()->get(),

        ]);
    }

    public function firstStepSubmit(): void
    {
        $this->validate($this->firstStepRules());
        $this->currentStep = 2;
    }

    public function secondStepSubmit(): void
    {
        $this->validate($this->secondStepRules());
        $this->currentStep = 3;
    }

    public function back(int $step): void
    {
        if ($step < 1 || $step > 3) {
            return;
        }

        $this->currentStep = $step;
    }

    protected function firstStepRules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:guardians,email'],
            'password' => ['required', 'string', 'min:6'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_name_en' => ['required', 'string', 'max:255'],
            'father_national_id' => ['required', 'string', 'max:10', 'min:10', 'unique:guardians,father_national_id'],
            'father_passport_id' => ['required', 'string', 'max:10', 'min:10', 'unique:guardians,father_passport_id'],
            'father_phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:15', 'min:10'],
            'father_job' => ['required', 'string', 'max:30'],
            'father_job_en' => ['required', 'string', 'max:30'],
            'father_nationality_id' => ['required', 'exists:nationalities,id'],
            'father_blood_type_id' => ['required', 'exists:blood_types,id'],
            'father_religion_id' => ['required', 'exists:religions,id'],
            'father_address' => ['required', 'string', 'max:200'],
        ];
    }

    protected function secondStepRules(): array
    {
        return [
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_name_en' => ['required', 'string', 'max:255'],
            'mother_national_id' => ['required', 'string', 'max:10', 'min:10', 'unique:guardians,mother_national_id'],
            'mother_passport_id' => ['required', 'string', 'max:10', 'min:10', 'unique:guardians,mother_passport_id'],
            'mother_phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'max:15', 'min:10'],
            'mother_job' => ['required', 'string', 'max:30'],
            'mother_job_en' => ['required', 'string', 'max:30'],
            'mother_nationality_id' => ['required', 'exists:nationalities,id'],
            'mother_blood_type_id' => ['required', 'exists:blood_types,id'],
            'mother_religion_id' => ['required', 'exists:religions,id'],
            'mother_address' => ['required', 'string', 'max:200'],
        ];
    }

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName, [
            'email' => 'required|email',
            'father_national_id' => 'required|string|min:10|max:10|regex:/[0-9]{9}/',
            'father_passport_id' => 'min:10|max:10',
            'father_phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'mother_national_id' => 'required|string|min:10|max:10|regex:/[0-9]{9}/',
            'mother_passport_id' => 'min:10|max:10',
            'mother_phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
    }

    public function submitForm()
    {

        try {
            $guardian = new Guardian;
            // Father_INPUTS
            $guardian->email = $this->email;
            $guardian->password = Hash::make($this->password);
            $guardian->father_name = ['en' => $this->father_name_en, 'ar' => $this->father_name];
            $guardian->father_national_id = $this->father_national_id;
            $guardian->father_passport_id = $this->father_passport_id;
            $guardian->father_phone = $this->father_phone;
            $guardian->father_job = ['en' => $this->father_job_en, 'ar' => $this->father_job];
            $guardian->father_passport_id = $this->father_passport_id;
            $guardian->father_nationality_id = $this->father_nationality_id;
            $guardian->father_blood_type_id = $this->father_blood_type_id;
            $guardian->father_religion_id = $this->father_religion_id;
            $guardian->father_address = $this->father_address;

            // Mother_INPUTS
            $guardian->mother_name = ['en' => $this->mother_name_en, 'ar' => $this->mother_name];
            $guardian->mother_national_id = $this->mother_national_id;
            $guardian->mother_passport_id = $this->mother_passport_id;
            $guardian->mother_phone = $this->mother_phone;
            $guardian->mother_job = ['en' => $this->mother_job_en, 'ar' => $this->mother_job];
            $guardian->mother_passport_id = $this->mother_passport_id;
            $guardian->mother_nationality_id = $this->mother_nationality_id;
            $guardian->mother_blood_type_id = $this->mother_blood_type_id;
            $guardian->mother_religion_id = $this->mother_religion_id;
            $guardian->mother_address = $this->mother_address;

            $guardian->save();
            $this->successMessage = trans('messages.success');
            $this->clearForm();
            $this->currentStep = 1;
        } catch (\Exception $e) {
            $this->catchError = $e->getMessage();
        }
    }

    // clearForm
    public function clearForm(): void
    {
        $this->email = null;
        $this->password = null;
        $this->father_name = null;
        $this->father_job = null;
        $this->father_job_en = null;
        $this->father_name_en = null;
        $this->father_national_id = null;
        $this->father_passport_id = null;
        $this->father_phone = null;
        $this->father_nationality_id = null;
        $this->father_blood_type_id = null;
        $this->father_address = null;
        $this->father_religion_id = null;

        $this->mother_name = null;
        $this->mother_job = null;
        $this->mother_job_en = null;
        $this->mother_name_en = null;
        $this->mother_national_id = null;
        $this->mother_passport_id = null;
        $this->mother_phone = null;
        $this->mother_nationality_id = null;
        $this->mother_blood_type_id = null;
        $this->mother_address = null;
        $this->mother_religion_id = null;
    }
}
