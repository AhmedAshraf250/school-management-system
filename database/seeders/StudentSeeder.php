<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Gender;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $genderIds = Gender::query()->pluck('id');
        $nationalityIds = Nationality::query()->pluck('id');
        $bloodTypeIds = BloodType::query()->pluck('id');
        $guardianIds = Guardian::query()->pluck('id');
        $sections = Section::query()->select(['id', 'grade_id', 'classroom_id'])->get();

        if (
            $genderIds->isEmpty() ||
            $nationalityIds->isEmpty() ||
            $bloodTypeIds->isEmpty() ||
            $guardianIds->isEmpty() ||
            $sections->isEmpty()
        ) {
            return;
        }

        $students = [
            ['en' => 'Ahmed Ibrahim', 'ar' => 'أحمد إبراهيم'],
            ['en' => 'Youssef Mahmoud', 'ar' => 'يوسف محمود'],
            ['en' => 'Laila Samir', 'ar' => 'ليلى سمير'],
            ['en' => 'Mariam Adel', 'ar' => 'مريم عادل'],
            ['en' => 'Khaled Nasser', 'ar' => 'خالد ناصر'],
        ];

        foreach ($students as $index => $studentName) {
            $serial = $index + 1;
            $section = $sections->random();

            Student::query()->updateOrCreate(
                ['email' => "student{$serial}@school.test"],
                [
                    'name' => $studentName,
                    'password' => Hash::make('12345678'),
                    'gender_id' => $genderIds->random(),
                    'nationality_id' => $nationalityIds->random(),
                    'blood_id' => $bloodTypeIds->random(),
                    'date_birth' => now()->subYears(rand(9, 16))->subDays(rand(0, 365))->toDateString(),
                    'grade_id' => $section->grade_id,
                    'classroom_id' => $section->classroom_id,
                    'section_id' => $section->id,
                    'guardian_id' => $guardianIds->random(),
                    'academic_year' => (string) now()->year,
                    'status' => Student::STATUS_ACTIVE,
                ]
            );
        }
    }
}
