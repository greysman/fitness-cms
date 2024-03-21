<?php

namespace App\Filament\Resources\Crm\SignupResource\Pages;

use App\Filament\Resources\Crm\SignupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSignup extends ViewRecord
{
    protected static string $resource = SignupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
