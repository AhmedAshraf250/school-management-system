<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = Grade::select('id', 'Name')->get();

        $existingSections = Section::query()->get();

        $Sections = [
            ['en' => 'a', 'ar' => 'ا'],
            ['en' => 'b', 'ar' => 'ب'],
            ['en' => 'c', 'ar' => 'ت'],
        ];

        foreach ($grades as $grade) {
            $classrooms = Classroom::query()
                ->where('grade_id', $grade->id)
                ->select(['id', 'name'])
                ->get();

            foreach ($classrooms as $classroom) {
                foreach ($Sections as $section) {
                    $matchedSection = $existingSections->first(function (Section $existingSection) use ($grade, $classroom, $section): bool {
                        return $existingSection->grade_id === $grade->id
                            && $existingSection->classroom_id === $classroom->id
                            && $existingSection->getTranslation('name', 'en') === $section['en'];
                    });

                    if ($matchedSection !== null) {
                        continue;
                    }

                    $newSection = Section::query()->create([
                        'name' => $section,
                        'status' => 1,
                        'grade_id' => $grade->id,
                        'classroom_id' => $classroom->id,
                    ]);

                    $existingSections->push($newSection);
                }
            }
        }
    }
}
