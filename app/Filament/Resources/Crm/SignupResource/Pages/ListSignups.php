<?php

namespace App\Filament\Resources\Crm\SignupResource\Pages;

use App\Filament\Resources\Crm\SignupResource;
use App\Filament\Resources\Crm\SignupResource\Widgets\TrainerScheduleWidget;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSignups extends ListRecords
{
    protected static string $resource = SignupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // TrainerScheduleWidget::class,
        ];
    }
}
