<?php

namespace App\Filament\Resources\Crm\ContactResource\RelationManagers;

use App\Models\Cms\Gym;
use App\Models\Crm\Request;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestRelationManager extends RelationManager
{
    protected static string $relationship = 'requests';

    protected static ?string $recordTitleAttribute = 'title';

    protected function canCreate(): bool
    {
        return false;
    }

    public static function getModelLabel(): string 
    {
        return __('requests.label');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('requests.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('requests.table.title'))
                    ->searchable(),
                BadgeColumn::make('stage_id')
                    ->label(__('requests.table.stage'))
                    ->enum(Request::stages())
                    ->colors([
                        'danger' => Request::STAGE_NEW, 
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('gym.title')
                    ->label(__('requests.table.gym'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->label(__('requests.table.source'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_profit')
                    ->label(__('requests.table.expected_profit.label'))
                    ->suffix(function (Request $record) {
                        return $record->expected_profit !== null ? __('requests.table.expected_profit.suffix') : null;
                    }),
                Tables\Columns\TextColumn::make('expected_close_date')
                    ->label(__('requests.table.expected_close_date'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->label(__('requests.table.responsible'))
                    ->url(function ($record) {
                        return $record->responsible
                            ? route('filament.resources.users.edit', [$record->responsible->id])
                            : null;
                    }, true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('requests.table.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('requests.table.editor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('lost_reason')
                    ->label(__('requests.table.lost_reason')),
                Tables\Columns\TextColumn::make('closed_at')
                    ->label(__('requests.table.closed_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('requests.table.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('requests.table.updated_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('requests.table.deleted_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('responsible')
                    ->label(__('requests.filters.responsible.value'))
                    ->query(fn (Builder $query): Builder => $query->where('responsible_id', Filament::auth()->user()->id)),
                SelectFilter::make('status_id')
                    ->label(__('requests.filters.status.label'))
                    ->options(Request::statuses()),
                SelectFilter::make('gym_id')
                    ->label(__('requests.filters.gym.label'))
                    ->options(Gym::all()->pluck('title', 'id')),
                SelectFilter::make('source_id')
                    ->label(__('requests.filters.source.label'))
                    ->options(Request::sources()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
