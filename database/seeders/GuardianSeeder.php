<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Guardian;
use App\Models\Nationality;
use App\Models\Religion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        $nationalityIds = Nationality::query()->pluck('id');
        $bloodTypeIds = BloodType::query()->pluck('id');
        $religionIds = Religion::query()->pluck('id');

        if ($nationalityIds->isEmpty() || $bloodTypeIds->isEmpty() || $religionIds->isEmpty()) {
            return;
        }

        $guardians = [
            ['en' => 'Omar Hassan', 'ar' => 'عمر حسن'],
            ['en' => 'Karim Ali', 'ar' => 'كريم علي'],
            ['en' => 'Yasser Adel', 'ar' => 'ياسر عادل'],
            ['en' => 'Tamer Nabil', 'ar' => 'تامر نبيل'],
            ['en' => 'Hany Ibrahim', 'ar' => 'هاني إبراهيم'],
        ];

        foreach ($guardians as $index => $guardianName) {
            $serial = $index + 1;

            Guardian::query()->updateOrCreate(
                ['email' => "guardian{$serial}@school.test"],
                [
                    'password' => Hash::make('12345678'),
                    'father_name' => $guardianName,
                    'father_national_id' => '30'.str_pad((string) $serial, 12, '1', STR_PAD_LEFT),
                    'father_passport_id' => 'P-F-'.$serial.str_pad((string) $serial, 4, '0', STR_PAD_LEFT),
                    'father_phone' => '010000000'.str_pad((string) $serial, 2, '0', STR_PAD_LEFT),
                    'father_job' => ['en' => 'Engineer', 'ar' => 'مهندس'],
                    'father_nationality_id' => $nationalityIds->random(),
                    'father_blood_type_id' => $bloodTypeIds->random(),
                    'father_religion_id' => $religionIds->random(),
                    'father_address' => 'Cairo',
                    'mother_name' => ['en' => 'Mother '.$serial, 'ar' => 'الأم '.$serial],
                    'mother_national_id' => '31'.str_pad((string) $serial, 12, '2', STR_PAD_LEFT),
                    'mother_passport_id' => 'P-M-'.$serial.str_pad((string) $serial, 4, '0', STR_PAD_LEFT),
                    'mother_phone' => '011000000'.str_pad((string) $serial, 2, '0', STR_PAD_LEFT),
                    'mother_job' => ['en' => 'Teacher', 'ar' => 'معلمة'],
                    'mother_nationality_id' => $nationalityIds->random(),
                    'mother_blood_type_id' => $bloodTypeIds->random(),
                    'mother_religion_id' => $religionIds->random(),
                    'mother_address' => 'Cairo',
                ]
            );
        }
    }
}
