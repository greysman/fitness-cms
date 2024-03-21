<?php

namespace App\Filament\Resources\Crm\CustomerReportResource\Pages;

use App\Filament\Resources\Crm\CustomerReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomerReports extends ManageRecords
{
    protected static string $resource = CustomerReportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
