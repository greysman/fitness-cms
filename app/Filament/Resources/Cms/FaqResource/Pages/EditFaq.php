<?php

namespace App\Filament\Resources\Cms\FaqResource\Pages;

use App\Filament\Resources\Cms\FaqResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
