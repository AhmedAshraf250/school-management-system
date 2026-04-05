<?php

namespace Database\Factories;

use App\Models\BloodType;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition()
    {
        $serial = $this->faker->unique()->numberBetween(100000, 999999);

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'father_name' => [
                'en' => $this->faker->name('male'),
                'ar' => 'ولي أمر '.$serial,
            ],
            'father_national_id' => '30'.$serial.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'father_passport_id' => 'FP-'.$serial,
            'father_phone' => '010'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'father_job' => [
                'en' => $this->faker->jobTitle(),
                'ar' => 'موظف',
            ],
            'father_nationality_id' => Nationality::query()->inRandomOrder()->value('id'),
            'father_blood_type_id' => BloodType::query()->inRandomOrder()->value('id'),
            'father_religion_id' => Religion::query()->inRandomOrder()->value('id'),
            'father_address' => $this->faker->city(),
            'mother_name' => [
                'en' => $this->faker->name('female'),
                'ar' => 'والدة '.$serial,
            ],
            'mother_national_id' => '31'.$serial.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'mother_passport_id' => 'MP-'.$serial,
            'mother_phone' => '011'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'mother_job' => [
                'en' => $this->faker->jobTitle(),
                'ar' => 'موظفة',
            ],
            'mother_nationality_id' => Nationality::query()->inRandomOrder()->value('id'),
            'mother_blood_type_id' => BloodType::query()->inRandomOrder()->value('id'),
            'mother_religion_id' => Religion::query()->inRandomOrder()->value('id'),
            'mother_address' => $this->faker->city(),
        ];
    }
}
