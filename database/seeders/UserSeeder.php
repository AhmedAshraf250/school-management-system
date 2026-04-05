<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@school.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('12345678'),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'ahmed@mail.com'],
            [
                'name' => 'Ahmed Ashraf',
                'password' => Hash::make('password'),
            ],
        );
    }
}
