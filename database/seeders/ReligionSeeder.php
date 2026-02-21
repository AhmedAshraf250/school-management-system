<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('religions')->delete();

        $religions = [

            [
                'en' => 'Muslim',
                'ar' => 'مسلم',
            ],
            [
                'en' => 'Christian',
                'ar' => 'مسيحي',
            ],
            [
                'en' => 'Other',
                'ar' => 'غيرذلك',
            ],

        ];

        foreach ($religions as $R) {
            Religion::updateOrCreate(
                ['name->en' => $R['en']],
                ['name' => $R]
            );
        }
    }
}
