<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Section;
use App\Models\Specialization;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    private const TARGET_TEACHERS = 20;

    public function run(): void
    {
        $genderIds = Gender::query()->pluck('id');
        $specializationIds = Specialization::query()->pluck('id');
        $sectionIds = Section::query()->pluck('id');

        if ($genderIds->isEmpty() || $specializationIds->isEmpty() || $sectionIds->isEmpty()) {
            return;
        }

        $teachers = collect();

        for ($index = 0; $index < self::TARGET_TEACHERS; $index++) {
            $serial = $index + 1;
            $email = $serial === 1
                ? 'teacher@mail.com'
                : sprintf('teacher%03d@school.test', $serial);

            $teacher = Teacher::query()->updateOrCreate(
                ['email' => $email],
                Teacher::factory()->raw([
                    'email' => $email,
                    'name' => [
                        'en' => 'Teacher '.$serial,
                        'ar' => 'المعلم '.$serial,
                    ],
                    'specialization_id' => $specializationIds->random(),
                    'gender_id' => $genderIds[$index % $genderIds->count()],
                    'joining_date' => now()->subYears(random_int(1, 8))->subDays(random_int(0, 300))->toDateString(),
                    'address' => 'Cairo',
                ])
            );

            $teachers->push($teacher);
        }

        $sections = $sectionIds->values();

        foreach ($sections as $position => $sectionId) {
            $teacher = $teachers[$position % $teachers->count()];
            $teacher->sections()->syncWithoutDetaching([$sectionId]);
        }

        foreach ($teachers as $teacher) {
            $extraSections = $sections->shuffle()->take(random_int(1, 3))->all();
            $teacher->sections()->syncWithoutDetaching($extraSections);
        }
    }
}
