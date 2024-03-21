<?php

namespace App\Filament\Pages;

use App\Settings\SmsSettings as SettingsSmsSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Pages\SettingsPage;

class SmsSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.additional.label');
    }

    protected static function getNavigationLabel(): string
    {
        return __('sms-settings.pluralLabel');
    }

    protected function getTitle(): string
    {
        return __('sms-settings.pluralLabel');
    }

    protected static string $settings = SettingsSmsSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Card::make([
                Checkbox::make('active')
                    ->label(__('sms-settings.fields.active.label')),
                Fieldset::make(__('sms-settings.fieldsets.customer_training_reminder.label'))
                    ->schema([
                        Checkbox::make('customer_training_reminder')
                            ->label(__('sms-settings.fields.customer_training_reminder.label')),
                        TextInput::make('customer_training_reminder_hours')
                            ->label(__('sms-settings.fields.customer_training_reminder_hours.label'))
                            ->suffix(__('sms-settings.fields.customer_training_reminder_hours.suffix')),
                        Textarea::make('customer_training_reminder_text')
                        ->label(__('sms-settings.fields.customer_training_reminder_text.label')),
                    ])
                    ->columns(1),
                Fieldset::make(__('sms-settings.fieldsets.customer_notify_about_expiration.label'))
                    ->schema([
                        Checkbox::make('customer_notify_about_expiration')
                            ->label(__('sms-settings.fields.customer_notify_about_expiration.label')),
                        Textarea::make('customer_notify_about_expiration_text')
                            ->label(__('sms-settings.fields.customer_notify_about_expiration_text.label')),
                    ])
                    ->columns(1),
                
                TimePicker::make('notification_time')
                    ->label(__('sms-settings.fields.notification_time.label'))
                    ->helperText(__('sms-settings.fields.notification_time.helperText'))
                    ->withoutSeconds()
            ])
                ->inlineLabel(),
        ];
    }
}
