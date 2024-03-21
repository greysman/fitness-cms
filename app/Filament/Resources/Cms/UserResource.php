<?php

namespace App\Filament\Resources\Cms;


use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentAuthentication\Actions\ImpersonateLink;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\CreateUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\EditUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ListUsers;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ViewUser;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class UserResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    public function __construct()
    {
        static::$model = config('filament-authentication.models.User');
    }

    protected static function getNavigationGroup(): ?string
    {
        return strval(__('filament-authentication::filament-authentication.section.group'));
    }

    public static function getLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.user'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.users'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([    
                        Grid::make(2)
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Toggle::make('active')
                                            ->label(__('filament-breezy::default.fields.active')),
                                        TextInput::make('name')
                                            ->label(strval(__('filament-authentication::filament-authentication.field.user.name')))
                                            ->required(),
                                        TextInput::make('surname')
                                            ->label(__('filament-breezy::default.fields.surname')),
                                        TextInput::make('patronymic')
                                            ->label(__('filament-breezy::default.fields.patronymic')),
                                        TextInput::make('email')
                                            ->required()
                                            ->email()
                                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                                            ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                                        PhoneInput::make('phone')
                                            ->label(__('filament-breezy::default.fields.phone'))
                                            ->initialCountry('ru')
                                            ->unique(config('filament-breezy.user_model'), 'phone', ignoreRecord: true),
                                        TextInput::make('telegram_user_id')
                                            ->unique(config('filament-breezy.user_model'), 'telegram_user_id', ignoreRecord: true)
                                            ->label(__('filament-breezy::default.fields.telegram_user_id.label'))
                                            ->hint(__('filament-breezy::default.fields.telegram_user_id.hint')),
                                        DatePicker::make('birthday')
                                            ->label(__('filament-breezy::default.fields.birthday')),
                                    ]),
                                FileUpload::make('avatar')
                                    ->label(__('filament-breezy::default.fields.avatar'))
                                    ->directory('profiles'),
                            ]),
                    ])->columns(2),
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->same('passwordConfirmation')
                                    ->password()
                                    ->maxLength(255)
                                    ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                                    ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : '')
                                    ->label(strval(__('filament-authentication::filament-authentication.field.user.password'))),
                                TextInput::make('passwordConfirmation')
                                    ->password()
                                    ->dehydrated(false)
                                    ->maxLength(255)
                                    ->label(strval(__('filament-authentication::filament-authentication.field.user.confirm_password'))),
                            ]),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(config('filament-authentication.preload_roles'))
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.id'))),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-breezy::default.fields.name'))),
                TextColumn::make('surname')
                    ->searchable()
                    ->sortable()
                    ->label(__('filament-breezy::default.fields.surname')),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->label(__('filament-breezy::default.fields.phone')),
                ToggleColumn::make('active')
                    ->label(__('filament-breezy::default.fields.active')),
                IconColumn::make('email_verified_at')
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn ($state): bool => $state === null,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn ($state): bool => $state === null,
                    ])
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.verified_at'))),
                TagsColumn::make('roles.name')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.created_at'))),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label(strval(__('filament-authentication::filament-authentication.filter.verified')))
                    ->nullable(),
            ])
            ->prependActions([
                ImpersonateLink::make(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
