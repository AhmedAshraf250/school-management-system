<?php

use App\Livewire\Calendar\CalendarWidget;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

test('admin can add calendar event from livewire widget', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin, 'admin');

    Livewire::test(CalendarWidget::class)
        ->call('addEvent', [
            'title_ar' => 'اجتماع أولياء الأمور',
            'title_en' => 'Parents Meeting',
            'start' => '2026-03-20',
        ]);

    $this->assertDatabaseHas('calendar_events', [
        'title_ar' => 'اجتماع أولياء الأمور',
        'title_en' => 'Parents Meeting',
    ]);
});

test('admin can move calendar event date', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin, 'admin');

    $event = CalendarEvent::query()->create([
        'title_ar' => 'اختبار قصير',
        'title_en' => 'Quiz',
        'starts_at' => Carbon::parse('2026-03-25 00:00:00'),
        'ends_at' => null,
    ]);

    Livewire::test(CalendarWidget::class)
        ->call('eventDrop', [
            'id' => $event->id,
            'start' => '2026-03-27',
            'end' => null,
        ]);

    $event->refresh();

    expect($event->starts_at?->format('Y-m-d'))->toBe('2026-03-27');
});
