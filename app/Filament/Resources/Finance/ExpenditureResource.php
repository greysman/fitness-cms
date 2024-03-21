<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\ExpenditureResource\Pages;
use App\Models\Expenditure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class ExpenditureResource extends Resource
{
    protected static ?string $model = Expenditure::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.finance.label');
    }

    public static function getModelLabel(): string
    {
        return __('expenditure.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('expenditure.pluralLabel');
    }

    protected static function getNavigationLabel(): string
    {
        return __('expenditure.navigationLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('expenditure.fields.title.label'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('type_id')
                    ->label(__('expenditure.fields.type.label'))
                    ->options(Expenditure::types())
                    ->default(Expenditure::TYPE_INCOME)
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label(__('expenditure.fields.comment.label'))
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Toggle::make('has_contact')
                    ->label(__('expenditure.fields.has_contact.label'))
                    ->helperText(__('expenditure.fields.has_contact.helper'))
                    ->default(false)
                    ->columnSpanFull(),
                Toggle::make('has_items')
                    ->label(__('expenditure.fields.has_items.label'))
                    ->helperText(__('expenditure.fields.has_items.helper'))
                    ->default(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('expenditure.fields.title.label')),
                BadgeColumn::make('type_id')
                    ->label(__('expenditure.fields.type.label'))
                    ->enum(Expenditure::types())
                    ->colors([
                        'success' => Expenditure::TYPE_INCOME,
                        'danger' => Expenditure::TYPE_EXPENDITURE,
                    ]),
                IconColumn::make('has_contact')
                    ->label(__('expenditure.fields.has_contact.label'))
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn ($state): bool => $state === false,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn ($state): bool => $state === false,
                    ]),
                IconColumn::make('has_items')
                    ->label(__('expenditure.fields.has_items.label'))
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn ($state): bool => $state === false,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn ($state): bool => $state === false,
                    ]),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('expenditure.fields.author.label')),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('expenditure.fields.editor.label')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('expenditure.fields.created_at.label'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('expenditure.fields.updated_at.label'))
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
            'index' => Pages\ManageExpenditures::route('/'),
        ];
    }    
}
