<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    private const STARTER_SUBJECTS = [
        ['en' => 'Mathematics', 'ar' => 'الرياضيات'],
        ['en' => 'Science', 'ar' => 'العلوم'],
        ['en' => 'Arabic Language', 'ar' => 'اللغة العربية'],
        ['en' => 'English Language', 'ar' => 'اللغة الإنجليزية'],
    ];

    public function run(): void
    {
        $teacherSectionScopes = Teacher::query()
            ->with([
                'sections' => fn ($query) => $query
                    ->select(['sections.id', 'sections.grade_id', 'sections.classroom_id'])
                    ->orderBy('sections.id'),
            ])
            ->orderBy('teachers.id')
            ->get(['teachers.id'])
            ->map(function (Teacher $teacher): ?array {
                $section = $teacher->sections->first();

                if ($section === null) {
                    return null;
                }

                return [
                    'teacher_id' => $teacher->id,
                    'grade_id' => $section->grade_id,
                    'classroom_id' => $section->classroom_id,
                ];
            })
            ->filter()
            ->values();

        if ($teacherSectionScopes->isEmpty()) {
            return;
        }

        foreach ($teacherSectionScopes as $index => $scope) {
            $subjectName = self::STARTER_SUBJECTS[$index % count(self::STARTER_SUBJECTS)];

            Subject::query()->updateOrCreate(
                [
                    'teacher_id' => $scope['teacher_id'],
                    'grade_id' => $scope['grade_id'],
                    'classroom_id' => $scope['classroom_id'],
                    'name->en' => $subjectName['en'],
                ],
                [
                    'name' => $subjectName,
                ]
            );
        }
    }
}
