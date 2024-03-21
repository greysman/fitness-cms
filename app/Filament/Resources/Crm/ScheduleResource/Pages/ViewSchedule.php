<?php

namespace App\Filament\Resources\Crm\ScheduleResource\Pages;

use App\Filament\Resources\Crm\ScheduleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSchedule extends ViewRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
