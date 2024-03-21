<?php

namespace App\Filament\Resources\Crm\SaleReportResource\Pages;

use App\Filament\Resources\Crm\SaleReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSaleReports extends ManageRecords
{
    protected static string $resource = SaleReportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
