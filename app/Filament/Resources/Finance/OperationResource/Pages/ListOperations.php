<?php

namespace App\Filament\Resources\Finance\OperationResource\Pages;

use App\Filament\Resources\Finance\OperationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOperations extends ListRecords
{
    protected static string $resource = OperationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
