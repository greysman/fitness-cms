<?php

namespace App\Filament\Resources\Blog\FilamentPageResource\Pages;

use App\Filament\Resources\Blog\FilamentPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFilamentPage extends CreateRecord
{
    public static function getResource(): string
    {
        return config('filament-pages.filament.resource', FilamentPageResource::class);
    }
}
