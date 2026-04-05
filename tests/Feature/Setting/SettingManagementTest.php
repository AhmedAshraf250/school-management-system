<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('settings page is accessible', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('settings.index'));

    $response->assertSuccessful();
});

test('can update settings and upload school logo', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    seedSettingsValues();

    $settingId = Setting::query()->value('id');

    $response = $this->actingAs($user)->put(route('settings.update', $settingId), [
        'school_name' => 'My New School',
        'school_title' => 'MNS',
        'phone' => '01000000000',
        'school_email' => 'info@myschool.test',
        'address' => 'Giza',
        'end_first_term' => '2026-12-31',
        'end_second_term' => '2027-05-31',
        'logo' => UploadedFile::fake()->image('school-logo.png'),
    ]);

    $response->assertRedirect(route('settings.index'));

    $this->assertDatabaseHas('settings', [
        'key' => 'school_name',
        'value' => 'My New School',
    ]);

    $logoPath = (string) Setting::query()->where('key', 'logo')->value('value');
    expect($logoPath)->toStartWith('attachments/settings/logo/');

    Storage::disk('public')->assertExists($logoPath);
});

function seedSettingsValues(): void
{
    $settings = [
        'school_name' => 'School Name',
        'school_title' => 'SN',
        'phone' => '0123456789',
        'school_email' => 'school@example.com',
        'address' => 'Cairo',
        'end_first_term' => '2025-12-31',
        'end_second_term' => '2026-05-31',
        'logo' => '',
    ];

    foreach ($settings as $key => $value) {
        Setting::query()->create([
            'key' => $key,
            'value' => $value,
        ]);
    }
}
