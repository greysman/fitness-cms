<?php

namespace App\Filament\Resources\Cms;

use App\Filament\Resources\Cms\ReviewResource\Pages;
use App\Filament\Resources\Cms\ReviewResource\RelationManagers;
use App\Models\Cms\Review;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::new()->count() ?: false;
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getModelLabel(): string
    {
        return __('reviews.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('reviews.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('reviews.name'))
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('text')
                    ->label(__('reviews.text'))
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status_id')
                    ->label(__('reviews.status'))
                    ->options(Review::statuses())
                    ->required(),
                ]);
            }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('reviews.name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('text')
                    ->label(__('reviews.text'))
                    ->limit(40),
                BadgeColumn::make('status_id')
                    ->label(__('reviews.status'))
                    ->enum(Review::statuses())
                    ->colors([
                        'danger' => static fn ($state): bool => $state == Review::STATUS_NEW,
                        'warning' => static fn ($state): bool => $state == Review::STATUS_MODERATED,
                        'success' => static fn ($state): bool => $state == Review::STATUS_PUBLISHED,
                        'secondary' => static fn ($state): bool => $state == Review::STATUS_SPAM,
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('reviews.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('reviews.updated_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('reviews.deleted_at'))
                    ->dateTime(),
            ])
            ->defaultSort('status_id', 'asc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReviews::route('/'),
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
