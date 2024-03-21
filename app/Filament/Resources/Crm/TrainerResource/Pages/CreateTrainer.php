<?php

namespace App\Filament\Resources\Crm\TrainerResource\Pages;

use App\Filament\Resources\Crm\TrainerResource;
use App\Models\Crm\Trainer;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();
            
            $this->callHook('afterValidate');
            
            $data = $this->mutateFormDataBeforeCreate($data);
            
            $this->callHook('beforeCreate');
            
            $this->record = $this->handleRecordCreation($data);
            
            $this->form->model($this->record)->saveRelationships();

            if (isset($data['trainer']) && !empty($data['trainer'])) {
                $this->record->trainer()->save(new Trainer($data['trainer']));
            }

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }
}
