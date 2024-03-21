<?php

namespace App\Filament\Resources\Finance\OperationResource\Pages;

use App\Filament\Resources\Finance\OperationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperation extends EditRecord
{
    protected static string $resource = OperationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
