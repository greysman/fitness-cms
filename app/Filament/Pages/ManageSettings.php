<?php

namespace App\Filament\Pages;

use App\Settings\AppSettings;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class ManageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.additional.label');
    }

    protected static function getNavigationLabel(): string
    {
        return __('settings.pluralLabel');
    }

    protected function getTitle(): string
    {
        return __('settings.pluralLabel');
    }

    protected static string $settings = AppSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('settings')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('general')
                        ->label(__('settings.tabs.general'))
                        ->inlineLabel()
                        ->schema([
                            TextInput::make('title')
                                ->label(__('settings.fields.title')),
                            Textarea::make('address')
                                ->label(__('settings.fields.address')),
                            Checkbox::make('trainer_notification')
                                ->label(__('settings.fields.trainer_notification')),
                            Checkbox::make('allow_registration')
                                ->label(__('settings.fields.allow_registration')),
                            RichEditor::make('working_hours')
                                ->label(__('settings.fields.working_hours')),
                        ]),
                    Tab::make('seo')
                        ->label(__('settings.tabs.seo'))
                        ->inlineLabel()
                        ->schema([
                            Fieldset::make('home_page')
                                ->label(__('settings.fieldsets.home_page'))
                                ->columns(1)
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label(__('settings.fields.meta_title')),
                                    Textarea::make('meta_description')
                                        ->label(__('settings.fields.meta_description')),
                                ]),
                        ]),
                    Tab::make('contact_data')
                        ->label(__('settings.tabs.contact_data'))
                        ->inlineLabel()
                        ->schema([
                            TextInput::make('contact_email')
                                ->label(__('settings.fields.contact_email'))
                                ->email(),
                            PhoneInput::make('phone_number')
                                ->initialCountry('ru')
                                ->label(__('settings.fields.phone_number')),
                        ]),
                    Tab::make('social')
                        ->label(__('settings.tabs.social'))
                        ->inlineLabel()
                        ->schema([
                            TextInput::make('instagram')
                                ->label(__('settings.fields.instagram')),
                            TextInput::make('facebook')
                                ->label(__('settings.fields.facebook')),
                            TextInput::make('vk')
                                ->label(__('settings.fields.vk')),
                        ])
                ]),
        ];
    }
}
