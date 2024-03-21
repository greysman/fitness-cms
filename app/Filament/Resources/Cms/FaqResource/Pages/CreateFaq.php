<?php

namespace App\Filament\Resources\Cms\FaqResource\Pages;

use App\Filament\Resources\Cms\FaqResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;
}
