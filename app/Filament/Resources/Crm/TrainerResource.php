<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\TrainerResource\Pages;
use App\Filament\Resources\Crm\TrainerResource\RelationManagers;
use App\Models\Crm\Trainer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class TrainerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'trainers';

    protected static ?int $navigationSort = -10;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static function getNavigationGroup(): string
    {
        return __('filament-authentication::filament-authentication.section.group');
    }

    public static function getModelLabel(): string 
    {
        return __('trainers.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('trainers.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        static::getPrimaryColumnSchema(),
                    ])
                    ->columnSpan([
                        'sm' => 'full',
                        'md' => 'full',
                        'lg' => 6
                    ]),
                static::getSecondaryColumnSchema(),
            ])
            ->columns([
                'sm' => 8,
                'lg' => null,
            ]);
    }

    public static function getPrimaryColumnSchema(): Component
    {
        return Tabs::make('trainer')
            ->tabs([
                Tab::make('user')
                    ->label(__('trainers.tabs.user'))
                    ->columns(['lg' => 2])
                    ->schema(static::getUserTabSchema()),
                Tab::make('trainer')
                    ->label(__('trainers.tabs.trainer'))
                    ->columns(['lg' => 2])
                    ->schema(static::getTrainerTabSchema()),
            ]);
    }

    public static function getUserTabSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(strval(__('filament-authentication::filament-authentication.field.user.name')))
                ->required(),
            TextInput::make('surname')
                ->label(__('filament-breezy::default.fields.surname')),
            TextInput::make('patronymic')
                ->label(__('filament-breezy::default.fields.patronymic')),
            Group::make()
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(table: static::$model, ignorable: fn ($record) => $record)
                        ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                    TextInput::make('telegram_user_id')
                        ->unique(config('filament-breezy.user_model'), 'telegram_user_id', ignoreRecord: true)
                        ->label(__('filament-breezy::default.fields.telegram_user_id.label'))
                        ->hint(__('filament-breezy::default.fields.telegram_user_id.hint')),
                    PhoneInput::make('phone')
                        ->label(__('filament-breezy::default.fields.phone'))
                        ->initialCountry('ru')
                        ->unique(config('filament-breezy.user_model'), 'phone', ignoreRecord: true),
                ]),
            DatePicker::make('birthday')
                ->label(__('filament-breezy::default.fields.birthday')),
        ];
    }

    public static function getTrainerTabSchema(): array
    {
        return [
            TextInput::make('trainer.specialization')
                ->label(__('trainers.fields.specialization'))
                ->columnSpanFull(),
            Textarea::make('trainer.education')
                ->label(__('trainers.fields.education'))
                ->columnSpanFull(),
        ];
    }

    public static function getSecondaryColumnSchema(): Component
    {
        return Card::make()
            ->schema([
                FileUpload::make('avatar')
                    ->disk('public')
                    ->directory('trainers')
                    ->label(__('filament-breezy::default.fields.avatar')),
                SpatieTagsInput::make('tags')
                    ->label(__('trainers.fields.tags'))
                    ->type('trainers'),
                TextInput::make('trainer.order')
                    ->label(__('trainers.fields.order'))
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('active')
                    ->label(__('filament-breezy::default.fields.active')),
                Toggle::make('trainer.published')
                    ->label(__('trainers.fields.published')),
            ])
            ->columnSpan(['lg' => 2]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ImageColumn::make('avatar')
                    ->disk('public')    
                    ->label(__('filament-breezy::default.fields.avatar'))
                    ->circular()
                    ->size(80),
                TextColumn::make('name')
                    ->label(__('filament-breezy::default.fields.name'))
                    ->sortable(),
                TextColumn::make('trainer.order')
                    ->label(__('trainers.fields.order'))
                    ->sortable(),
                ToggleColumn::make('active')
                    ->label(__('filament-breezy::default.fields.active'))
                    ->sortable(),
                ToggleColumn::make('trainer.published')
                    ->label(__('trainers.fields.published'))
                    ->sortable(),
                TextColumn::make('trainer.specialization')
                    ->label(__('trainers.fields.specialization')),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.created_at')))
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->label(__('trainers.fields.deleted_at'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainers::route('/'),
            'create' => Pages\CreateTrainer::route('/create'),
            'edit' => Pages\EditTrainer::route('/{record}/edit'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('trainer')
            ->whereHas('trainer')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
