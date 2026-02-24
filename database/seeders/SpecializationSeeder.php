<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            ['en' => 'Arabic', 'ar' => 'عربي'],
            ['en' => 'Mathematics', 'ar' => 'رياضيات'],
            ['en' => 'Social Studies', 'ar' => 'دراسات اجتماعية'],
            ['en' => 'Sciences', 'ar' => 'علوم'],
            ['en' => 'Computer', 'ar' => 'حاسب الي'],
            ['en' => 'English', 'ar' => 'انجليزي'],
        ];
        foreach ($specializations as $s) {
            Specialization::updateOrCreate(
                ['name->en' => $s['en']],
                ['name' => $s]
            );
        }
    }
}
