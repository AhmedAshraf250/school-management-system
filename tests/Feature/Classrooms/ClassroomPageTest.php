<?php

use App\Models\Grade;
use App\Models\User;

test('classrooms page clears repeater handlers before re-initializing it', function () {
    $user = User::factory()->create();

    Grade::query()->create([
        'Name' => [
            'ar' => 'المرحلة الأولى',
            'en' => 'First Stage',
        ],
        'Notes' => 'Test grade',
    ]);

    $response = $this->actingAs($user)->get(route('classrooms.index'));

    $response->assertOk();
    $response->assertSee("repeater.find('[data-repeater-create]').off('click');", false);
    $response->assertSee("repeaterList.off('click', '[data-repeater-delete]');", false);
});
