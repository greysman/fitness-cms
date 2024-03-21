<?php

namespace App\Filament\Resources\Cms\FaqResource\Pages;

use App\Filament\Resources\Cms\FaqResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
