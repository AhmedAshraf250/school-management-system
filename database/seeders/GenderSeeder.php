<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['en' => 'Male', 'ar' => 'ذكر'],
            ['en' => 'Female', 'ar' => 'انثي'],
        ];

        foreach ($genders as $gender) {
            Gender::query()->updateOrCreate(
                ['name->en' => $gender['en']],
                ['name' => $gender],
            );
        }
    }
}
