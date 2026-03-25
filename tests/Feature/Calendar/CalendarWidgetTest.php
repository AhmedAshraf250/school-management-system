<?php

use App\Livewire\Calendar\CalendarWidget;
use App\Models\CalendarEvent;
use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
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
        'teacher_id' => null,
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

test('teacher can add and move only own calendar events', function () {
    $teacher = createCalendarTeacher();
    $this->actingAs($teacher, 'teacher');

    Livewire::test(CalendarWidget::class)
        ->call('addEvent', [
            'title_ar' => 'مراجعة قبل الامتحان',
            'title_en' => 'Exam revision',
            'start' => '2026-03-29',
        ]);

    $event = CalendarEvent::query()->where('teacher_id', $teacher->id)->firstOrFail();

    Livewire::test(CalendarWidget::class)
        ->call('eventDrop', [
            'id' => $event->id,
            'start' => '2026-03-30',
            'end' => null,
        ]);

    $event->refresh();

    expect($event->starts_at?->format('Y-m-d'))->toBe('2026-03-30');
});

test('teacher cannot move admin calendar events', function () {
    $teacher = createCalendarTeacher();
    $this->actingAs($teacher, 'teacher');

    $adminEvent = CalendarEvent::query()->create([
        'teacher_id' => null,
        'title_ar' => 'فعالية عامة',
        'title_en' => 'General event',
        'starts_at' => Carbon::parse('2026-04-01 00:00:00'),
        'ends_at' => null,
    ]);

    Livewire::test(CalendarWidget::class)
        ->call('eventDrop', [
            'id' => $adminEvent->id,
            'start' => '2026-04-02',
            'end' => null,
        ])
        ->assertForbidden();
});

function createCalendarTeacher(): Teacher
{
    $gender = Gender::query()->create(['name' => 'Male']);
    $specialization = Specialization::query()->create(['name' => 'Science']);

    return Teacher::query()->create([
        'email' => 'calendar-teacher@example.com',
        'password' => Hash::make('password'),
        'name' => 'Calendar Teacher',
        'specialization_id' => $specialization->id,
        'gender_id' => $gender->id,
        'joining_date' => '2026-01-01',
        'address' => 'Cairo',
    ]);
}
