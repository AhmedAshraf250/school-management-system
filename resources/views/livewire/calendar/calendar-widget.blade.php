<div>
    {{-- Calendar widget container --}}
    <div class="card card-statistics">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ trans('main_trans.dashboard_calendar_title') }}</h5>
            <div id="calendar-container" wire:ignore>
                <div id="dashboard-calendar"></div>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                const initializeDashboardCalendar = function() {
                    const calendarElement = document.getElementById('dashboard-calendar');
                    if (!calendarElement || calendarElement.dataset.initialized === '1') {
                        return;
                    }

                    if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.fullCalendar !== 'function') {
                        console.error('Calendar initialization failed: fullCalendar plugin is not available.');
                        return;
                    }

                    if (typeof window.Livewire === 'undefined') {
                        console.error('Calendar initialization failed: Livewire is not available.');
                        return;
                    }

                    const componentRoot = calendarElement.closest('[wire\\:id]');
                    if (!componentRoot) {
                        return;
                    }

                    const component = Livewire.find(componentRoot.getAttribute('wire:id'));
                    if (!component) {
                        return;
                    }

                    const isEditable = @json($editable);
                    const isCompact = @json($compact);

                    calendarElement.dataset.initialized = '1';

                    const calendarConfig = {
                        locale: @json(app()->getLocale()),
                        editable: isEditable,
                        selectable: isEditable,
                        displayEventTime: false,
                        height: isCompact ? 420 : 650,
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: isCompact ? 'month' : 'month,agendaWeek,agendaDay',
                        },
                        events: function(start, end, timezone, callback) {
                            component.call('loadEvents').then(function(events) {
                                callback(events);
                            }).catch(function(error) {
                                console.error(error);
                                callback([]);
                            });
                        }
                    };

                    if (isEditable) {
                        calendarConfig.dayClick = function(date) {
                            const titleAr = prompt(@json(trans('main_trans.calendar_prompt_title_ar')));
                            if (titleAr === null || titleAr.trim() === '') {
                                alert(@json(trans('main_trans.calendar_title_required')));
                                return;
                            }

                            const titleEn = prompt(@json(trans('main_trans.calendar_prompt_title_en')), titleAr);
                            if (titleEn === null || titleEn.trim() === '') {
                                alert(@json(trans('main_trans.calendar_title_required')));
                                return;
                            }

                            component.call('addEvent', {
                                title_ar: titleAr.trim(),
                                title_en: titleEn.trim(),
                                start: date.format(),
                            }).then(function() {
                                window.jQuery('#dashboard-calendar').fullCalendar('refetchEvents');
                                alert(@json(trans('main_trans.calendar_event_added')));
                            });
                        };

                        calendarConfig.eventDrop = function(event) {
                            component.call('eventDrop', {
                                id: event.id,
                                start: event.start ? event.start.format() : null,
                                end: event.end ? event.end.format() : null,
                            }).then(function() {
                                window.jQuery('#dashboard-calendar').fullCalendar('refetchEvents');
                            });
                        };
                    }

                    window.jQuery('#dashboard-calendar').fullCalendar(calendarConfig);

                    Livewire.on('refresh-calendar', function() {
                        window.jQuery('#dashboard-calendar').fullCalendar('refetchEvents');
                    });
                };

                document.addEventListener('DOMContentLoaded', initializeDashboardCalendar);
                document.addEventListener('livewire:init', initializeDashboardCalendar);
                window.addEventListener('load', initializeDashboardCalendar);

                if (document.readyState !== 'loading') {
                    initializeDashboardCalendar();
                }
            </script>
        @endpush
    @endonce
</div>
