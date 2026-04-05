<?php

namespace Database\Factories;

use App\Models\BloodType;
use App\Models\Gender;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        $section = Section::query()->inRandomOrder()->first(['id', 'grade_id', 'classroom_id']);

        return [
            'name' => [
                'en' => $this->faker->name(),
                'ar' => 'طالب '.$this->faker->unique()->numberBetween(1000, 9999),
            ],
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'gender_id' => Gender::query()->inRandomOrder()->value('id'),
            'nationality_id' => Nationality::query()->inRandomOrder()->value('id'),
            'blood_id' => BloodType::query()->inRandomOrder()->value('id'),
            'date_birth' => $this->faker->dateTimeBetween('-17 years', '-6 years')->format('Y-m-d'),
            'grade_id' => $section?->grade_id,
            'classroom_id' => $section?->classroom_id,
            'section_id' => $section?->id,
            'guardian_id' => Guardian::query()->inRandomOrder()->value('id'),
            'academic_year' => (string) now()->year,
            'status' => Student::STATUS_ACTIVE,
        ];
    }
}
