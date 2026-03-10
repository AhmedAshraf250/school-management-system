<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'current_session' => '2026-2027',
            'school_title' => 'SMS',
            'school_name' => 'School Management System',
            'end_first_term' => '2026-12-31',
            'end_second_term' => '2027-05-31',
            'phone' => '0123456789',
            'address' => 'Cairo',
            'school_email' => 'info@schoolms.test',
            'logo' => '',
        ];

        foreach ($data as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
