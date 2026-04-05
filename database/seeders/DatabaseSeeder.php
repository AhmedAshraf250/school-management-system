<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(ClassroomSeeder::class);
        $this->call(SectionSeeder::class);
        $this->call(BloodTypeSeeder::class);
        $this->call(NationalitySeeder::class);
        $this->call(ReligionSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(SpecializationSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(GuardianSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(FeeSeeder::class);
    }
}
