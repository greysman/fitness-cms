<?php

namespace App\Filament\Resources\Crm\TrainerResource\Pages;

use App\Filament\Resources\Crm\TrainerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainers extends ListRecords
{
    protected static string $resource = TrainerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
