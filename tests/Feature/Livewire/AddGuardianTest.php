<?php

use App\Livewire\AddGuardian;
use App\Models\BloodType;
use App\Models\Nationality;
use App\Models\Religion;
use Livewire\Livewire;

test('first step does not advance when required father data is missing', function () {
    Livewire::test(AddGuardian::class)
        ->call('firstStepSubmit')
        ->assertHasErrors([
            'email' => 'required',
            'password' => 'required',
            'father_name' => 'required',
        ])
        ->assertSet('currentStep', 1);
});

test('first step advances when father data is valid', function () {
    $nationality = Nationality::query()->create([
        'name' => ['ar' => 'مصري', 'en' => 'Egyptian'],
    ]);
    $bloodType = BloodType::query()->create(['name' => 'O+']);
    $religion = Religion::query()->create([
        'name' => ['ar' => 'مسلم', 'en' => 'Muslim'],
    ]);

    Livewire::test(AddGuardian::class)
        ->set('email', 'father@example.com')
        ->set('password', 'secret123')
        ->set('father_name', 'أحمد')
        ->set('father_name_en', 'Ahmed')
        ->set('father_national_id', '1234567890')
        ->set('father_passport_id', 'P123456789')
        ->set('father_phone', '01000000000')
        ->set('father_job', 'مهندس')
        ->set('father_job_en', 'Engineer')
        ->set('father_nationality_id', $nationality->id)
        ->set('father_blood_type_id', $bloodType->id)
        ->set('father_religion_id', $religion->id)
        ->set('father_address', 'Cairo')
        ->call('firstStepSubmit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

test('second step advances when mother data is valid', function () {
    $nationality = Nationality::query()->create([
        'name' => ['ar' => 'مصري', 'en' => 'Egyptian'],
    ]);
    $bloodType = BloodType::query()->create(['name' => 'A+']);
    $religion = Religion::query()->create([
        'name' => ['ar' => 'مسيحي', 'en' => 'Christian'],
    ]);

    Livewire::test(AddGuardian::class)
        ->set('currentStep', 2)
        ->set('mother_name', 'منى')
        ->set('mother_name_en', 'Mona')
        ->set('mother_national_id', '1234567890')
        ->set('mother_passport_id', 'M987654321')
        ->set('mother_phone', '01000000001')
        ->set('mother_job', 'معلمة')
        ->set('mother_job_en', 'Teacher')
        ->set('mother_nationality_id', $nationality->id)
        ->set('mother_blood_type_id', $bloodType->id)
        ->set('mother_religion_id', $religion->id)
        ->set('mother_address', 'Giza')
        ->call('secondStepSubmit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3);
});
