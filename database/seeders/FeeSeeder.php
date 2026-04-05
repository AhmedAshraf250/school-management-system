<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $activeAcademicYear = (string) now()->year;
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Classroom> $classrooms */
        $classrooms = Classroom::query()
            ->with('grade:id,Name')
            ->select(['id', 'grade_id', 'name'])
            ->get();

        if ($classrooms->isEmpty()) {
            return;
        }

        foreach ($classrooms as $classroom) {
            $gradeName = (string) optional($classroom->grade)->getTranslation('Name', 'en');
            $baseAmount = match ($gradeName) {
                'Middle School' => 15500,
                'High School' => 19000,
                default => 12000,
            };

            $classroomEnName = (string) $classroom->getTranslation('name', 'en');
            $classroomArName = (string) $classroom->getTranslation('name', 'ar');

            Fee::query()->updateOrCreate(
                [
                    'grade_id' => $classroom->grade_id,
                    'classroom_id' => $classroom->id,
                    'year' => $activeAcademicYear,
                    'type' => 1,
                ],
                [
                    'title' => [
                        'en' => 'Tuition Fees - '.$classroomEnName.' - '.$activeAcademicYear,
                        'ar' => 'الرسوم الدراسية - '.$classroomArName.' - '.$activeAcademicYear,
                    ],
                    'amount' => $baseAmount + ($classroom->id % 3) * 500,
                    'grade_id' => $classroom->grade_id,
                    'classroom_id' => $classroom->id,
                    'description' => 'Official annual tuition fee',
                    'year' => $activeAcademicYear,
                    'type' => 1,
                ]
            );
        }
    }
}
