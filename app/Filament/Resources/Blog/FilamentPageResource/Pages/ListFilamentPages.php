<?php

namespace App\Filament\Resources\Blog\FilamentPageResource\Pages;

use App\Filament\Resources\Blog\FilamentPageResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFilamentPages extends ListRecords
{
    public static function getResource(): string
    {
        return config('filament-pages.filament.resource', FilamentPageResource::class);
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
