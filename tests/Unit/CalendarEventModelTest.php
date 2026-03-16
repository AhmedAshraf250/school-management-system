<?php

use App\Models\CalendarEvent;

test('calendar event model defines expected fillable attributes', function () {
    $calendarEvent = new CalendarEvent;

    expect($calendarEvent->getFillable())->toBe([
        'title_ar',
        'title_en',
        'starts_at',
        'ends_at',
    ]);
});

test('calendar event model casts date fields to datetime', function () {
    $calendarEvent = new CalendarEvent;
    $casts = $calendarEvent->getCasts();

    expect($casts)->toHaveKey('starts_at', 'datetime');
    expect($casts)->toHaveKey('ends_at', 'datetime');
});
