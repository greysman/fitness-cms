<?php

namespace App\Filament\Resources\Cms\TagResource\Pages;

use App\Filament\Resources\Cms\TagResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTags extends ManageRecords
{
    protected static string $resource = TagResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
