<?php

namespace App\Filament\Resources\Crm\ContactResource\Pages;

use App\Filament\Resources\Crm\ContactResource;
use App\Jobs\Sms\SendSmsMessageJob;
use App\Settings\SmsSettings;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContact extends EditRecord
{
    protected static string $resource = ContactResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('sendSms')
                ->label('Отправить SMS')
                ->form([
                    Textarea::make('message')
                        ->label('Сообщение')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $phoneNumber = $this->record->phone;
                    $message = $data['message'];

                    if (app(SmsSettings::class)->active && $phoneNumber) {
                        SendSmsMessageJob::dispatch($phoneNumber, $message);
                        Notification::make()
                            ->title('Сообщение отправлено')
                            ->send();
                    }
                }),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
