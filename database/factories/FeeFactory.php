<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fee>
 */
class FeeFactory extends Factory
{
    protected $model = Fee::class;

    public function definition(): array
    {
        $classroom = Classroom::query()->inRandomOrder()->first(['id', 'grade_id']);
        $year = (string) now()->year;

        return [
            'title' => [
                'en' => 'Annual Tuition '.$year,
                'ar' => 'الرسوم الدراسية السنوية '.$year,
            ],
            'amount' => $this->faker->numberBetween(8000, 25000),
            'grade_id' => $classroom?->grade_id,
            'classroom_id' => $classroom?->id,
            'description' => 'Official school tuition fee',
            'year' => $year,
            'type' => 1,
        ];
    }
}
