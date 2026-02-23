<?php

use App\Livewire\AddGuardian;
use App\Models\BloodType;
use App\Models\Guardian;
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

test('submit form validates father and mother data before persistence', function () {
    Livewire::test(AddGuardian::class)
        ->call('submitForm')
        ->assertHasErrors([
            'email' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
        ]);
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

test('edit populates guardian data into latest component variables', function () {
    $nationality = Nationality::query()->create([
        'name' => ['ar' => 'مصري', 'en' => 'Egyptian'],
    ]);
    $bloodType = BloodType::query()->create(['name' => 'B+']);
    $religion = Religion::query()->create([
        'name' => ['ar' => 'مسلم', 'en' => 'Muslim'],
    ]);

    $guardian = Guardian::query()->create([
        'email' => 'guardian@example.com',
        'password' => bcrypt('secret123'),
        'father_name' => ['ar' => 'الأب', 'en' => 'Father'],
        'father_national_id' => '1234567890',
        'father_passport_id' => 'P123456789',
        'father_phone' => '01000000000',
        'father_job' => ['ar' => 'مهندس', 'en' => 'Engineer'],
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => $religion->id,
        'father_address' => 'Cairo',
        'mother_name' => ['ar' => 'الأم', 'en' => 'Mother'],
        'mother_national_id' => '2234567890',
        'mother_passport_id' => 'M123456789',
        'mother_phone' => '01000000001',
        'mother_job' => ['ar' => 'معلمة', 'en' => 'Teacher'],
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => $religion->id,
        'mother_address' => 'Giza',
    ]);

    Livewire::test(AddGuardian::class)
        ->call('edit', $guardian->id)
        ->assertSet('updateMode', true)
        ->assertSet('guardian_id', $guardian->id)
        ->assertSet('email', 'guardian@example.com')
        ->assertSet('father_name', 'الأب')
        ->assertSet('father_name_en', 'Father')
        ->assertSet('mother_name', 'الأم')
        ->assertSet('mother_name_en', 'Mother');
});

test('first step unique checks ignore current guardian while editing', function () {
    $nationality = Nationality::query()->create([
        'name' => ['ar' => 'مصري', 'en' => 'Egyptian'],
    ]);
    $bloodType = BloodType::query()->create(['name' => 'AB+']);
    $religion = Religion::query()->create([
        'name' => ['ar' => 'مسيحي', 'en' => 'Christian'],
    ]);

    $guardian = Guardian::query()->create([
        'email' => 'edit@example.com',
        'password' => bcrypt('secret123'),
        'father_name' => ['ar' => 'الأب', 'en' => 'Father'],
        'father_national_id' => '1234567890',
        'father_passport_id' => 'P123456789',
        'father_phone' => '01000000000',
        'father_job' => ['ar' => 'مهندس', 'en' => 'Engineer'],
        'father_nationality_id' => $nationality->id,
        'father_blood_type_id' => $bloodType->id,
        'father_religion_id' => $religion->id,
        'father_address' => 'Cairo',
        'mother_name' => ['ar' => 'الأم', 'en' => 'Mother'],
        'mother_national_id' => '2234567890',
        'mother_passport_id' => 'M123456789',
        'mother_phone' => '01000000001',
        'mother_job' => ['ar' => 'معلمة', 'en' => 'Teacher'],
        'mother_nationality_id' => $nationality->id,
        'mother_blood_type_id' => $bloodType->id,
        'mother_religion_id' => $religion->id,
        'mother_address' => 'Giza',
    ]);

    Livewire::test(AddGuardian::class)
        ->call('edit', $guardian->id)
        ->call('firstStepSubmit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});
