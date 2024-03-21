<?php

namespace App\Filament\Resources\Finance\OperationResource\Pages;

use App\Filament\Resources\Finance\OperationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOperation extends CreateRecord
{
    protected static string $resource = OperationResource::class;
}
