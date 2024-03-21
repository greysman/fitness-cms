<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store\CategoryResource\Pages;
use App\Filament\Resources\Store\CategoryResource\RelationManagers;
use App\Models\Store\Category;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.store.label');
    }

    public static function getModelLabel(): string
    {
        return __('categories.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('categories.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TitleWithSlugInput::make(
                        fieldTitle: 'title',
                        fieldSlug: 'slug',
                        titlePlaceholder: __('categories.form.title.label'),
                        urlVisitLinkLabel: __('filament-pages::filament-pages.filament.form.slug.visit_link.label'),
                    )->columnSpanFull(),
                    Select::make('parent_id')
                        ->label(__('categories.form.parent.label'))
                        ->options(function ($record) {
                            return !$record
                                ? Category::all()->pluck('title', 'id')
                                : Category::whereNot('id', $record->id)->get()->pluck('title', 'id');
                        })
                        ->searchable(),
                    RichEditor::make('description')
                        ->maxLength(65535),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('categories.table.title')),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('categories.table.slug')),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('categories.table.author')),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('categories.table.editor')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('categories.table.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('categories.table.updated_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('categories.table.deleted_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('parent')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
