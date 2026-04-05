<?php

namespace Database\Factories;

use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'name' => [
                'en' => $this->faker->name(),
                'ar' => 'معلم '.$this->faker->unique()->numberBetween(100, 999),
            ],
            'specialization_id' => Specialization::query()->inRandomOrder()->value('id'),
            'gender_id' => Gender::query()->inRandomOrder()->value('id'),
            'joining_date' => $this->faker->dateTimeBetween('-8 years', '-6 months')->format('Y-m-d'),
            'address' => $this->faker->address(),
        ];
    }
}
