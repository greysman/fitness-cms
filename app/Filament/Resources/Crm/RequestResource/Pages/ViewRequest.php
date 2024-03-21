<?php

namespace App\Filament\Resources\Crm\RequestResource\Pages;

use App\Filament\Resources\Crm\RequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRequest extends ViewRecord
{
    protected static string $resource = RequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
