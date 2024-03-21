<?php

namespace App\Filament\Resources\Crm\TrainerReportResource\Pages;

use App\Filament\Resources\Crm\TrainerReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTrainerReports extends ManageRecords
{
    protected static string $resource = TrainerReportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
