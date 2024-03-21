<?php

namespace App\Filament\Resources\Crm\TrainerResource\Pages;

use App\Filament\Resources\Crm\TrainerResource;
use App\Models\Crm\Trainer;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditTrainer extends EditRecord
{
    // public $tags;
    // public $trainer;

    protected static string $resource = TrainerResource::class;

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');
        $data = array_merge(
            $this->getRecord()->attributesToArray(),
            ['trainer' => $this->getRecord()->trainer->attributesToArray()],
        );

        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    // protected function getFormModel(): User 
    // {
    //     return $this->user;
    // }

    // public function save(bool $shouldRedirect = true): void
    // {
    //     $this->user->update(
    //         $this->form->getState(),
    //     );
    // } 

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            // dd($this->form->getState());
            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            if (isset($data['trainer']) && !empty($data['trainer'])) {
                if ($trainer = $this->record->trainer) {
                    $trainer->update($data['trainer']);
                } else {
                    $this->record->trainer()->save(new Trainer($data['trainer']));
                }
            }

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()?->send();

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
