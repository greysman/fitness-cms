<?php

namespace App\Filament\Resources\Crm\SignupResource\Widgets;

use App\Filament\Resources\Crm\ScheduleResource;
use App\Models\Crm\Schedule;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Hidden;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class TrainerScheduleWidget extends FullCalendarWidget
{
    protected array $fullCalendarConfig = [
        'headerToolbar' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'timeGridWeek,timeGridDay,listDay',
        ],

        'initialView' => 'timeGridWeek',
        'slotDuration' => '00:30:00',
        'editable' => false,

        'selectable' => false,
    ];

    protected string $modalWidth = 'full';

    public static function canEdit(?array $event = null): bool
    {
        return false;
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $events = [];
        $schedules = Schedule::with(['gym', 'trainer'])
            ->whereDate('date', '>=', $fetchInfo['start'])
            ->whereDate('date', '<=', $fetchInfo['end'])
            ->orderBy('date', 'ASC')
            ->get();
        
        foreach ($schedules as $key => $schedule) {
            $color = $schedule->gym->color ?: 'rgb(79 70 229/var(--tw-bg-opacity))';
            
            $events[] = [
                'id' => $schedule->id,
                'title' => $schedule->title 
                    ? $schedule->title . ' | ' . $schedule->trainer->fullname
                    : $schedule->trainer->fullname,
                'start' => $schedule->date,
                'end' => $schedule->date_end,
                // 'url' => route('filament.resources.crm/schedules.view', [$schedule]),
                'shouldOpenInNewTab' => true,
                'color' => $color,
            ];
        }
        
        return $events;
    }

    /**
     * Triggered when the user clicks an event.
     */
    public function onEventClick($event): void
    {
        // parent::onEventClick($event);
    }

    public function createEvent(array $data): void
    {
        Schedule::create($data);

        $this->refreshEvents();
        // Create the event with the provided $data.
    }

    public function onCreateEventClick(array $date): void
    {
        if (! static::canCreate()) {
            return;
        }

        $this->evaluate($this->handleCreateEventClickUsing(), [
            'data' => $date,
        ]);

        $this->dispatchBrowserEvent('open-modal', ['id' => 'fullcalendar--create-event-modal']);
    }

    protected function handleCreateEventClickUsing(): Closure
    {
        return function ($data, FullCalendarWidget $calendar) {
            $timezone = $this->config('timeZone') !== ' local'
                ? $this->config('timeZone', config('app.timezone'))
                : config('app.timezone');

            if (isset($data['date'])) { // for single date click
                $end = $start = Carbon::parse($data['date'], $timezone);
            } else { // for date range select
                $start = Carbon::parse($data['start'], $timezone);
                $end = Carbon::parse($data['end'], $timezone);

                if ($data['allDay']) {
                    /**
                     * date is exclusive, read more https://fullcalendar.io/docs/select-callback
                     * For example, if the selection is all-day and the last day is a Thursday, end will be Friday.
                     */
                    $end->subDay()->endOfDay();
                }
            }

            $calendar->createEventForm->fill(['date' => $start, 'date_end' => $end]);
        };
    }

    protected static function getCreateEventFormSchema(): array
    {
        return array_merge(
            [
                Hidden::make('start')->reactive(),
                Hidden::make('end')->reactive(),
            ],
            ScheduleResource::getForm()
        );
    }

    /**
     * Triggered when dragging stops and the event has moved to a different day/time.
     */
    public function onEventDrop($newEvent, $oldEvent, $relatedEvents): void
    {
        $this->refreshEvents();
    }

    public function getCreateEventModalTitle(): string 
    {
        return __('filament::resources/pages/create-record.title', ['label' => __('schedules.modelLabel')]);
    }
}
