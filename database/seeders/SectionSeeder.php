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

        $sections = [
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
                foreach ($sections as $section) {
                    Section::query()->updateOrCreate(
                        [
                            'grade_id' => $grade->id,
                            'classroom_id' => $classroom->id,
                            'name->en' => $section['en'],
                        ],
                        [
                            'name' => $section,
                            'status' => 1,
                            'grade_id' => $grade->id,
                            'classroom_id' => $classroom->id,
                        ]
                    );
                }
            }
        }
    }
}
