<?php

namespace App\Filament\Resources\Crm\RequestResource\Pages;

use App\Filament\Resources\Crm\RequestResource;
use App\Filament\Resources\Crm\RequestResource\Widgets\Overdue;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequests extends ListRecords
{
    protected static string $resource = RequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            Overdue::class,
        ];
    }
}
