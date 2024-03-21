<?php

namespace App\Filament\Resources\Crm\SignupResource\Pages;

use App\Filament\Resources\Crm\SignupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSignup extends EditRecord
{
    protected static string $resource = SignupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['rating']) && $data['rating']) {
            $data['rating'] = (int) $data['rating'];
        }
    
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
