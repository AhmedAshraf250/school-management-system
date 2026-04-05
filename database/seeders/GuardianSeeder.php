<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use Illuminate\Database\Seeder;

class GuardianSeeder extends Seeder
{
    private const TOTAL_STUDENTS = 100;

    private const UNIQUE_GUARDIAN_RATIO = 0.8;

    public function run(): void
    {
        $nationalityIds = Nationality::query()->pluck('id');
        $bloodTypeIds = BloodType::query()->pluck('id');
        $religionIds = Religion::query()->pluck('id');

        if ($nationalityIds->isEmpty() || $bloodTypeIds->isEmpty() || $religionIds->isEmpty()) {
            return;
        }

        $targetGuardians = (int) ceil(self::TOTAL_STUDENTS * self::UNIQUE_GUARDIAN_RATIO);

        for ($index = 0; $index < $targetGuardians; $index++) {
            $serial = $index + 1;
            $email = $serial === 1
                ? 'guardian@mail.com'
                : sprintf('guardian%03d@school.test', $serial);

            Guardian::query()->updateOrCreate(
                ['email' => $email],
                Guardian::factory()->raw([
                    'email' => $email,
                    'father_name' => [
                        'en' => 'Guardian Father '.$serial,
                        'ar' => 'ولي الأمر '.$serial,
                    ],
                    'father_national_id' => '30'.str_pad((string) $serial, 12, '1', STR_PAD_LEFT),
                    'father_passport_id' => 'P-F-'.str_pad((string) $serial, 5, '0', STR_PAD_LEFT),
                    'father_phone' => '010'.str_pad((string) $serial, 8, '0', STR_PAD_LEFT),
                    'father_job' => [
                        'en' => 'Engineer',
                        'ar' => 'مهندس',
                    ],
                    'father_nationality_id' => $nationalityIds->random(),
                    'father_blood_type_id' => $bloodTypeIds->random(),
                    'father_religion_id' => $religionIds->random(),
                    'father_address' => 'Cairo',
                    'mother_name' => [
                        'en' => 'Guardian Mother '.$serial,
                        'ar' => 'ولية الأمر '.$serial,
                    ],
                    'mother_national_id' => '31'.str_pad((string) $serial, 12, '2', STR_PAD_LEFT),
                    'mother_passport_id' => 'P-M-'.str_pad((string) $serial, 5, '0', STR_PAD_LEFT),
                    'mother_phone' => '011'.str_pad((string) $serial, 8, '0', STR_PAD_LEFT),
                    'mother_job' => [
                        'en' => 'Teacher',
                        'ar' => 'معلمة',
                    ],
                    'mother_nationality_id' => $nationalityIds->random(),
                    'mother_blood_type_id' => $bloodTypeIds->random(),
                    'mother_religion_id' => $religionIds->random(),
                    'mother_address' => 'Cairo',
                ])
            );
        }
    }
}
