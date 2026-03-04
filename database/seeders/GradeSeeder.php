<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['en' => 'Primry Stage', 'ar' => 'المرحلة الإبتدائية'],
            ['en' => 'Middle School', 'ar' => 'المرحلة الإعدادية'],
            ['en' => 'High School', 'ar' => 'المرحلة الثانوية'],
        ];

        $existingGrades = Grade::query()->get();

        foreach ($grades as $grade) {
            $matchedGrade = $existingGrades->first(function (Grade $existingGrade) use ($grade): bool {
                return $existingGrade->getTranslation('Name', 'en') === $grade['en'];
            });

            if ($matchedGrade !== null) {
                continue;
            }

            $newGrade = Grade::query()->create([
                'Name' => $grade,
            ]);

            $existingGrades->push($newGrade);
        }
    }
}
