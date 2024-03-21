<?php

namespace App\Filament\Resources\Cms;

use App\Filament\Resources\Cms\FaqResource\Pages;
use App\Filament\Resources\Cms\FaqResource\RelationManagers;
use App\Models\Cms\Faq;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.content.label');
    }

    public static function getModelLabel(): string
    {
        return __('faq.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('faq.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label(__('faq.status'))
                            ->required(),
                    ]),
                
                Forms\Components\Textarea::make('question')
                    ->label(__('faq.question'))
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->label(__('faq.answer'))
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('order')
                    ->label(__('faq.order'))
                    ->numeric()
                    ->required()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label(__('faq.question'))
                    ->limit(30),
                Tables\Columns\TextColumn::make('answer')
                    ->label(__('faq.answer'))
                    ->limit(40),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('faq.order')),
                Tables\Columns\IconColumn::make('status_id')
                    ->label(__('faq.status'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('faq.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('faq.updated_at'))
                    ->dateTime(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
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
