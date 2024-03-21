<?php

namespace App\Filament\Resources\Cms\GymResource\Pages;

use App\Filament\Resources\Cms\GymResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGyms extends ManageRecords
{
    protected static string $resource = GymResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
