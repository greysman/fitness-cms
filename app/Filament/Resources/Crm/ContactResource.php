<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\ContactResource\Pages;
use App\Filament\Resources\Crm\ContactResource\RelationManagers;
use App\Models\Crm\Contact;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.crm.label');
    }

    public static function getLabel(): string 
    {
        return __('contacts.label');
    }

    public static function getModelLabel(): string 
    {
        return __('contacts.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('contacts.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema(static::getPrimaryColumnSchema())
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

    public static function getPrimaryColumnSchema()
    {
        return [
            Card::make()
            ->schema([
                Group::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('contacts.fields.name')) 
                        ->maxLength(255),
                    Forms\Components\TextInput::make('surname')
                        ->label(__('contacts.fields.surname'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('patronymic')
                        ->label(__('contacts.fields.patronymic'))
                        ->maxLength(255),
                ])
                ->columnSpanFull()
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 2
                ]),
                Group::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                        PhoneInput::make('phone')
                            ->label(__('filament-breezy::default.fields.phone'))
                            ->required()
                            ->initialCountry('ru')
                            ->unique('contacts', 'phone', ignoreRecord: true),
                    ]),
                DatePicker::make('birthday')
                    ->label(__('filament-breezy::default.fields.birthday')),
                Select::make('sex')
                    ->label(__('contacts.fields.sex.label'))
                    ->options([
                        0 => __('contacts.fields.sex.options.male'),
                        1 => __('contacts.fields.sex.options.female')
                    ])
            ])
            ->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 2
            ]),

            Card::make()
                ->schema([
                    RichEditor::make('comment')
                        ->label(__('contacts.fields.comment'))
                        ->fileAttachmentsDisk('local')
                        ->fileAttachmentsDirectory('attachments')
                        ->fileAttachmentsVisibility('private')
                ])
        ];
    }

    public static function getSecondaryColumnSchema()
    {
        return Card::make()
            ->schema([
                FileUpload::make('avatar')
                    ->directory('contacts')
                    ->disk('local')
                    ->visibility('private')
                    ->label(__('filament-breezy::default.fields.avatar')),
                Toggle::make('active')
                    ->label(__('filament-breezy::default.fields.active')),
                DateTimePicker::make('last_activity')
                    ->label(__('contacts.fields.last_activity'))
                    ->disabled()
            ])
            ->columnSpan(['lg' => 2]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ImageColumn::make('avatar')
                    ->label(__('filament-breezy::default.fields.avatar'))
                    ->circular()
                    ->size(80),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('contacts.fields.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->label(__('contacts.fields.surname'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('patronymic')
                    ->label(__('contacts.fields.patronymic'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('contacts.fields.email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('contacts.fields.phone'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label(__('contacts.fields.birthday'))
                    ->date()
                    ->sortable(),
                ToggleColumn::make('active')
                    ->label(__('filament-breezy::default.fields.active'))
                    ->sortable(),
                IconColumn::make('email_verified_at')
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn ($state): bool => $state === null,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn ($state): bool => $state === null,
                    ])
                    ->label(strval(__('contacts.fields.email_verified_at')))
                    ->sortable(),
                IconColumn::make('phone_verified_at')
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn ($state): bool => $state === null,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn ($state): bool => $state === null,
                    ])
                    ->label(strval(__('contacts.fields.phone_verified_at')))
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('contacts.fields.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('contacts.fields.editor'))
                    ->sortable(),
                BadgeColumn::make('sex')
                    ->label(__('contacts.fields.sex.label'))
                    ->enum([
                        0 => __('contacts.fields.sex.short_options.m'),
                        1 => __('contacts.fields.sex.short_options.w'),
                    ])
                    ->colors([
                        'primary' => 0,
                        'danger' => 1,
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('contacts.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('contacts.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('contacts.fields.deleted_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_activity')
                    ->label(__('contacts.fields.last_activity'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->label(__('contacts.filter.active.label'))
                    ->options([
                        0 => __('contacts.filter.active.options.inactive'),
                        1 => __('contacts.filter.active.options.active')
                    ]),
                SelectFilter::make('sex')
                    ->label(__('contacts.filter.sex.label'))
                    ->options([
                        0 => __('contacts.filter.sex.options.male'),
                        1 => __('contacts.filter.sex.options.female'),
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\SignupsRelationManager::class,
            RelationManagers\RequestRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
