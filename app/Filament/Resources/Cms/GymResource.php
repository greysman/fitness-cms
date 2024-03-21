<?php

namespace App\Filament\Resources\Cms;

use App\Filament\Resources\Cms\GymResource\Pages;
use App\Filament\Resources\Cms\GymResource\RelationManagers;
use App\Models\Cms\Gym;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class GymResource extends Resource
{
    protected static ?string $model = Gym::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.additional.label');
    }

    public static function getModelLabel(): string
    {
        return __('gyms.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('gyms.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ColorPicker::make('color')
                    ->label(__('gyms.color'))
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label(__('gyms.title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label(__('gyms.address'))
                    ->required()
                    ->maxLength(255),
                PhoneInput::make('phone')
                    ->label(__('gyms.phone'))
                    ->required()
                    ->initialCountry('ru'),
                Repeater::make('messangers')
                    ->label(__('gyms.messangers'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(7)
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('gyms.messanger_title'))
                                    ->required()
                                    ->columnSpan(2),
                                Select::make('type')
                                    ->label(__('gyms.messanger_type'))
                                    ->required()
                                    ->columnSpan(2)
                                    ->options(Gym::types()),
                                TextInput::make('url')
                                    ->label(__('gyms.messanger_url'))
                                    ->required()
                                    ->columnSpan(3),
                            ])
                        
                    ]),
                // Forms\Components\Textarea::make('messangers'),
                Forms\Components\TextInput::make('position')
                    ->label(__('gyms.position'))
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(__('gyms.color')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('gyms.title')),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('gyms.address')),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('gyms.phone')),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('gyms.position')),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('gyms.author')),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('gyms.editor')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('gyms.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('gyms.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGyms::route('/'),
        ];
    }    
}
