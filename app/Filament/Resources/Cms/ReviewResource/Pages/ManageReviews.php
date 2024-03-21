<?php

namespace App\Filament\Resources\Cms\ReviewResource\Pages;

use App\Filament\Resources\Cms\ReviewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReviews extends ManageRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
