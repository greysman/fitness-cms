<?php

namespace App\Filament\Resources\Cms;

use App\Filament\Resources\Cms\TagResource\Pages;
use App\Filament\Resources\Cms\TagResource\RelationManagers;
use App\Models\Cms\Tag;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.additional.label');
    }

    public static function getModelLabel(): string
    {
        return __('tags.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('tags.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TitleWithSlugInput::make(
                    fieldTitle: 'name',
                    fieldSlug: 'slug',
                    titlePlaceholder: __('tags.fields.name'),
                    urlVisitLinkLabel: false,
                )
                ->columnSpan('full'),
                Forms\Components\TextInput::make('type')
                    ->label(__('tags.fields.type'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_column')
                    ->label(__('tags.fields.order'))
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tags.fields.name')),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('tags.fields.slug')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('tags.fields.type'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_column')
                    ->label(__('tags.fields.order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tags.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('tags.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                
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
            'index' => Pages\ManageTags::route('/'),
        ];
    }    
}
