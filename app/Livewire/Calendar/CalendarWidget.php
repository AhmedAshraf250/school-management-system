<?php

namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class CalendarWidget extends Component
{
    public bool $editable = true;

    public bool $compact = false;

    public function mount(bool $editable = true, bool $compact = false): void
    {
        $this->editable = $editable;
        $this->compact = $compact;
    }

    public function loadEvents(): array
    {
        return CalendarEvent::query()
            ->orderBy('starts_at')
            ->get()
            ->map(fn (CalendarEvent $event): array => $this->toCalendarEventPayload($event))
            ->values()
            ->all();
    }

    public function addEvent(array $eventData): void
    {
        $this->authorizeEventMutation();
        $validated = $this->validateCreateEventData($eventData);

        CalendarEvent::query()->create([
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'starts_at' => Carbon::parse($validated['start']),
            'ends_at' => $this->parseOptionalDate($validated['end'] ?? null),
        ]);

        $this->dispatch('refresh-calendar');
    }

    public function eventDrop(array $eventData): void
    {
        $this->authorizeEventMutation();
        $validated = $this->validateMoveEventData($eventData);

        $event = CalendarEvent::query()->findOrFail((int) $validated['id']);

        $event->update([
            'starts_at' => Carbon::parse($validated['start']),
            'ends_at' => $this->parseOptionalDate($validated['end'] ?? null),
        ]);

        $this->dispatch('refresh-calendar');
    }

    public function render(): View
    {
        return view('livewire.calendar.calendar-widget');
    }

    private function authorizeEventMutation(): void
    {
        abort_unless($this->editable, 403);
        abort_unless(Auth::guard('admin')->check(), 403);
    }

    private function validateCreateEventData(array $eventData): array
    {
        return Validator::make($eventData, [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ])->validate();
    }

    private function validateMoveEventData(array $eventData): array
    {
        return Validator::make($eventData, [
            'id' => ['required', 'integer', 'exists:calendar_events,id'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ])->validate();
    }

    private function parseOptionalDate(?string $date): ?Carbon
    {
        if ($date === null || $date === '') {
            return null;
        }

        return Carbon::parse($date);
    }

    private function toCalendarEventPayload(CalendarEvent $event): array
    {
        return [
            'id' => (string) $event->id,
            'title' => app()->getLocale() === 'ar' ? $event->title_ar : $event->title_en,
            'start' => $event->starts_at?->toIso8601String(),
            'end' => $event->ends_at?->toIso8601String(),
            'allDay' => $event->ends_at === null,
        ];
    }
}
