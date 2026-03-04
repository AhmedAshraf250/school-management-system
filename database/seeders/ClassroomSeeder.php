<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::select('id', 'Name')->get();
        $existingClassrooms = Classroom::query()->get();

        $classrooms = [
            ['en' => 'First grade', 'ar' => 'الصف الاول'],
            ['en' => 'Second grade', 'ar' => 'الصف الثاني'],
            ['en' => 'Third grade', 'ar' => 'الصف الثالث'],
        ];

        foreach ($grades as $grade) {
            foreach ($classrooms as $classroom) {
                $matchedClassroom = $existingClassrooms->first(function (Classroom $existingClassroom) use ($grade, $classroom): bool {
                    return $existingClassroom->grade_id === $grade->id
                        && $existingClassroom->getTranslation('name', 'en') === $classroom['en'];
                });

                if ($matchedClassroom !== null) {
                    continue;
                }

                $newClassroom = Classroom::query()->create([
                    'name' => $classroom,
                    'grade_id' => $grade->id,
                ]);

                $existingClassrooms->push($newClassroom);
            }
        }
    }
}
