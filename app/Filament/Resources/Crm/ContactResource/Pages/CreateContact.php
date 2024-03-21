<?php

namespace App\Filament\Resources\Crm\ContactResource\Pages;

use App\Filament\Resources\Crm\ContactResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
