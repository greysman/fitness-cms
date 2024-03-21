<?php

namespace App\Filament\Resources\Finance\ExpenditureResource\Pages;

use App\Filament\Resources\Finance\ExpenditureResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExpenditures extends ManageRecords
{
    protected static string $resource = ExpenditureResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
