<?php

namespace App\Filament\Resources\Crm\ScheduleResource\Pages;

use App\Filament\Resources\Crm\ScheduleResource;
use App\Filament\Resources\Crm\ScheduleResource\Widgets\ScheduleWidget;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ScheduleWidget::class,
        ];
    }
}
