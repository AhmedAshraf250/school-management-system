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

        $classrooms = [
            ['en' => 'First grade', 'ar' => 'الصف الاول'],
            ['en' => 'Second grade', 'ar' => 'الصف الثاني'],
            ['en' => 'Third grade', 'ar' => 'الصف الثالث'],
        ];

        foreach ($grades as $grade) {
            foreach ($classrooms as $classroom) {
                Classroom::query()->updateOrCreate(
                    [
                        'grade_id' => $grade->id,
                        'name->en' => $classroom['en'],
                    ],
                    [
                        'name' => $classroom,
                        'grade_id' => $grade->id,
                    ]
                );
            }
        }
    }
}
