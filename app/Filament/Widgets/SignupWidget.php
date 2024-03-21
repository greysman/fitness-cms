<?php

namespace App\Filament\Widgets;

use App\Models\Crm\Signup;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class SignupWidget extends FullCalendarWidget
{
    protected static ?int $sort = 10;

    protected array $fullCalendarConfig = [
        'headerToolbar' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'timeGridWeek,timeGridDay,listDay',
        ],

        'initialView' => 'listDay',

        'slotDuration' => '00:30:00',

        'editable' => false,

        'selectable' => false,
    ];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(?array $event = null): bool
    {
        return false;
    }

    public static function canView(?array $event = null): bool
    {
        return true;
    }

    /**
     * Return events that should be rendered statically on calendar.
     */
    // public function getViewData(): array
    // {
    //     return [];
    // }


    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $events = [];
        $colors = [
            Signup::STATUS_NEW => 'red',
            Signup::STATUS_PROCESSING => 'green',
            Signup::STATUS_FINISHED => 'blue',
            Signup::STATUS_CANCELED => 'grey',
        ];
        $signups = Signup::with(['contact', 'gym', 'trainer'])
            ->whereDate('date', '>=', $fetchInfo['start'])
            ->whereDate('date', '<=', $fetchInfo['end'])
            ->orderBy('date', 'ASC')
            ->get();
        
        foreach ($signups as $key => $signup) {
            $color = $signup->gym->color ?: 'rgb(79 70 229/var(--tw-bg-opacity))';

            $events[] = [
                'id' => $signup->id,
                'title' => $signup->contact->fullname . ' - ' . $signup->gym->title,
                'start' => $signup->date,
                'end' => $signup->date->addMinutes($signup->duration),
                'url' => route('filament.resources.crm/signups.view', [$signup->id]),
                'shouldOpenInNewTab' => true,
                'color' => $color
            ];
        }

        return $events;
    }

    /**
     * Triggered when the user clicks an event.
     */
    public function onEventClick($event): void
    {
        parent::onEventClick($event);

        // your code
    }
}
