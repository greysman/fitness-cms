<?php

namespace App\Filament\Resources\Crm\SubscriptionReportResource\Pages;

use App\Filament\Resources\Crm\SubscriptionReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubscriptionReports extends ManageRecords
{
    protected static string $resource = SubscriptionReportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
