<?php

namespace App\Livewire\Calendar;

use App\Models\CalendarEvent;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
        return $this->visibleEventsQuery()
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
            'teacher_id' => $this->resolveOwnerTeacherId(),
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'starts_at' => Carbon::parse($validated['start']),
            'ends_at' => $this->parseOptionalDate($validated['end'] ?? null),
        ]);

        $this->dispatch('refresh-calendar');
    }

    public function eventDrop(array $eventData): void
    {
        $validated = $this->validateMoveEventData($eventData);

        $event = CalendarEvent::query()->findOrFail((int) $validated['id']);
        $this->authorizeEventMutation($event);

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

    private function authorizeEventMutation(?CalendarEvent $event = null): void
    {
        abort_unless($this->editable, 403);

        if (Auth::guard('admin')->check()) {
            return;
        }

        /** @var Teacher|null $teacher */
        $teacher = Auth::guard('teacher')->user();
        abort_unless($teacher instanceof Teacher, 403);

        if ($event !== null) {
            abort_unless((int) $event->teacher_id === (int) $teacher->id, 403);
        }
    }

    private function resolveOwnerTeacherId(): ?int
    {
        if (Auth::guard('admin')->check()) {
            return null;
        }

        /** @var Teacher|null $teacher */
        $teacher = Auth::guard('teacher')->user();
        abort_unless($teacher instanceof Teacher, 403);

        return $teacher->id;
    }

    private function visibleEventsQuery(): Builder
    {
        $query = CalendarEvent::query();

        if (Auth::guard('admin')->check()) {
            return $query;
        }

        /** @var Teacher|null $teacher */
        $teacher = Auth::guard('teacher')->user();
        if ($teacher instanceof Teacher) {
            return $query->where(function (Builder $calendarQuery) use ($teacher): void {
                $calendarQuery->whereNull('teacher_id')
                    ->orWhere('teacher_id', $teacher->id);
            });
        }

        /** @var Student|null $student */
        $student = Auth::guard('student')->user();
        if ($student instanceof Student) {
            $teacherIds = $student->section?->teachers()->pluck('teachers.id')->all() ?? [];

            return $query->where(function (Builder $calendarQuery) use ($teacherIds): void {
                $calendarQuery->whereNull('teacher_id');

                if ($teacherIds !== []) {
                    $calendarQuery->orWhereIn('teacher_id', $teacherIds);
                }
            });
        }

        /** @var Guardian|null $guardian */
        $guardian = Auth::guard('guardian')->user();
        if ($guardian instanceof Guardian) {
            $teacherIds = Teacher::query()
                ->whereHas('sections.students', function (Builder $studentQuery) use ($guardian): void {
                    $studentQuery->where('guardian_id', $guardian->id);
                })
                ->pluck('teachers.id')
                ->all();

            return $query->where(function (Builder $calendarQuery) use ($teacherIds): void {
                $calendarQuery->whereNull('teacher_id');

                if ($teacherIds !== []) {
                    $calendarQuery->orWhereIn('teacher_id', $teacherIds);
                }
            });
        }

        return $query->whereNull('teacher_id');
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
        /** @var Teacher|null $teacher */
        $teacher = Auth::guard('teacher')->user();

        return [
            'id' => (string) $event->id,
            'title' => app()->getLocale() === 'ar' ? $event->title_ar : $event->title_en,
            'start' => $event->starts_at?->toIso8601String(),
            'end' => $event->ends_at?->toIso8601String(),
            'allDay' => $event->ends_at === null,
            'editable' => Auth::guard('admin')->check() || ($teacher instanceof Teacher
                && (int) $event->teacher_id === (int) $teacher->id),
        ];
    }
}
