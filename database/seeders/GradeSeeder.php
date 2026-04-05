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
            ['en' => 'Primary Stage', 'ar' => 'المرحلة الإبتدائية'],
            ['en' => 'Middle School', 'ar' => 'المرحلة الإعدادية'],
            ['en' => 'High School', 'ar' => 'المرحلة الثانوية'],
        ];

        foreach ($grades as $grade) {
            Grade::query()->updateOrCreate(
                ['Name->en' => $grade['en']],
                ['Name' => $grade],
            );
        }
    }
}
