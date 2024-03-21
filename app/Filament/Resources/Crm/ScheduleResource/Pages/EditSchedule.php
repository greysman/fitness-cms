<?php

namespace App\Filament\Resources\Crm\ScheduleResource\Pages;

use App\Filament\Resources\Crm\ScheduleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
