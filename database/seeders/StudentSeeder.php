<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Gender;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    private const TOTAL_STUDENTS = 100;

    public function run(): void
    {
        $genderIds = Gender::query()->pluck('id');
        $nationalityIds = Nationality::query()->pluck('id');
        $bloodTypeIds = BloodType::query()->pluck('id');
        $guardianIds = Guardian::query()->orderBy('id')->pluck('id')->values();
        $sections = Section::query()
            ->with('grade:id,Name')
            ->select(['id', 'grade_id', 'classroom_id'])
            ->get();

        if (
            $genderIds->isEmpty() ||
            $nationalityIds->isEmpty() ||
            $bloodTypeIds->isEmpty() ||
            $guardianIds->isEmpty() ||
            $sections->isEmpty()
        ) {
            return;
        }

        $gradeGroupTargets = [
            'primary' => (int) round(self::TOTAL_STUDENTS * 0.5),
            'middle' => (int) round(self::TOTAL_STUDENTS * 0.3),
            'high' => self::TOTAL_STUDENTS - ((int) round(self::TOTAL_STUDENTS * 0.5) + (int) round(self::TOTAL_STUDENTS * 0.3)),
        ];

        $sectionsByGradeGroup = $sections
            ->groupBy(function (Section $section) {
                $gradeName = (string) optional($section->grade)->getTranslation('Name', 'en');

                return match ($gradeName) {
                    'Middle School' => 'middle',
                    'High School' => 'high',
                    default => 'primary',
                };
            })
            ->map(fn ($gradeGroupSections) => $gradeGroupSections->values());

        $activeAcademicYear = (string) now()->year;

        $guardianDistribution = $this->guardianAssignments($guardianIds, self::TOTAL_STUDENTS);
        $studentSerial = 1;

        foreach ($gradeGroupTargets as $gradeGroup => $targetCount) {
            $gradeGroupSections = $sectionsByGradeGroup->get($gradeGroup, collect())->values();

            if ($gradeGroupSections->isEmpty()) {
                continue;
            }

            $usedSections = $gradeGroupSections->shuffle()->values();

            for ($index = 0; $index < $targetCount; $index++) {
                $section = $usedSections[$index % $usedSections->count()];
                $isMale = $studentSerial % 2 === 1;
                $genderId = $genderIds[($studentSerial - 1) % $genderIds->count()];
                $guardianId = $guardianDistribution[$studentSerial - 1] ?? $guardianIds->random();
                $email = $studentSerial === 1
                    ? 'student@mail.com'
                    : sprintf('student%04d@school.test', $studentSerial);

                Student::query()->updateOrCreate(
                    ['email' => $email],
                    Student::factory()->raw([
                        'name' => [
                            'en' => $this->studentEnglishName($studentSerial, $isMale),
                            'ar' => $this->studentArabicName($studentSerial, $isMale),
                        ],
                        'email' => $email,
                        'gender_id' => $genderId,
                        'nationality_id' => $nationalityIds->random(),
                        'blood_id' => $bloodTypeIds->random(),
                        'date_birth' => $this->dateOfBirthByGradeGroup($gradeGroup),
                        'grade_id' => $section->grade_id,
                        'classroom_id' => $section->classroom_id,
                        'section_id' => $section->id,
                        'guardian_id' => $guardianId,
                        'academic_year' => $activeAcademicYear,
                        'status' => Student::STATUS_ACTIVE,
                    ])
                );

                $studentSerial++;
            }
        }
    }

    private function guardianAssignments($guardianIds, int $targetStudents): array
    {
        $assignments = [];
        $studentCountPerGuardian = [];
        $totalGuardians = $guardianIds->count();

        for ($index = 0; $index < min($targetStudents, $totalGuardians); $index++) {
            $guardianId = (int) $guardianIds[$index];
            $assignments[] = $guardianId;
            $studentCountPerGuardian[$guardianId] = 1;
        }

        while (count($assignments) < $targetStudents) {
            $selectedGuardianId = (int) $guardianIds->random();

            if (($studentCountPerGuardian[$selectedGuardianId] ?? 0) >= 3) {
                continue;
            }

            $assignments[] = $selectedGuardianId;
            $studentCountPerGuardian[$selectedGuardianId] = ($studentCountPerGuardian[$selectedGuardianId] ?? 0) + 1;
        }

        return $assignments;
    }

    private function dateOfBirthByGradeGroup(string $gradeGroup): string
    {
        return match ($gradeGroup) {
            'primary' => now()->subYears(random_int(6, 11))->subDays(random_int(0, 365))->toDateString(),
            'middle' => now()->subYears(random_int(12, 14))->subDays(random_int(0, 365))->toDateString(),
            default => now()->subYears(random_int(15, 17))->subDays(random_int(0, 365))->toDateString(),
        };
    }

    private function studentEnglishName(int $serial, bool $isMale): string
    {
        $maleFirstNames = ['Omar', 'Youssef', 'Karim', 'Mahmoud', 'Adel', 'Mostafa', 'Tarek', 'Hassan'];
        $femaleFirstNames = ['Mariam', 'Nour', 'Laila', 'Salma', 'Farah', 'Aya', 'Hana', 'Yasmin'];
        $lastNames = ['Ibrahim', 'Hassan', 'Mahmoud', 'Saber', 'El-Sayed', 'Fathy', 'Nabil', 'Kamal'];

        $firstNames = $isMale ? $maleFirstNames : $femaleFirstNames;
        $firstName = $firstNames[$serial % count($firstNames)];
        $lastName = $lastNames[$serial % count($lastNames)];

        return $firstName.' '.$lastName.' '.$serial;
    }

    private function studentArabicName(int $serial, bool $isMale): string
    {
        $maleFirstNames = ['عمر', 'يوسف', 'كريم', 'محمود', 'عادل', 'مصطفى', 'طارق', 'حسن'];
        $femaleFirstNames = ['مريم', 'نور', 'ليلى', 'سلمى', 'فرح', 'آية', 'هنا', 'ياسمين'];
        $lastNames = ['إبراهيم', 'حسن', 'محمود', 'صابر', 'السيد', 'فتحي', 'نبيل', 'كمال'];

        $firstNames = $isMale ? $maleFirstNames : $femaleFirstNames;
        $firstName = $firstNames[$serial % count($firstNames)];
        $lastName = $lastNames[$serial % count($lastNames)];

        return $firstName.' '.$lastName.' '.$serial;
    }
}
