<?php

namespace App\Filament\Resources\Crm\RequestResource\RelationManagers;

use App\Filament\Resources\Crm\EventResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'subject';

    public static function getModelLabel(): string 
    {
        return __('requests.relations.events.label');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('requests.relations.events.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(EventResource::getCreateEditForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label(trans('timex::timex.event.subject')),
                TextColumn::make('body')
                    ->label(trans('timex::timex.event.body'))
                    ->wrap()
                    ->limit(100),
                TextColumn::make('start')
                    ->label(trans('timex::timex.event.start'))
                    ->date()
                    ->description(fn($record) => $record->startTime),
                TextColumn::make('end')
                    ->label(trans('timex::timex.event.end'))
                    ->date()
                    ->description(fn($record)=> $record->endTime),
                BadgeColumn::make('category')
                    ->label(trans('timex::timex.event.category'))
                    ->enum(config('timex.categories.labels'))
                    ->formatStateUsing(function ($record){
                        if (Str::isUuid($record->category)){
                            return self::getCategoryModel() == null ? "" : self::getCategoryModel()::findOrFail($record->category)->getAttributes()[self::getCategoryModelColumn('value')];
                        }else{
                            return config('timex.categories.labels')[$record->category] ?? "";
                        }
                    })
                    ->color(function ($record){
                        if (Str::isUuid($record->category)){
                            return self::getCategoryModel() == null ? "primary" :self::getCategoryModel()::findOrFail($record->category)->getAttributes()[self::getCategoryModelColumn('color')];
                        }else{
                            return config('timex.categories.colors')[$record->category] ?? "primary";
                        }
                    })
            ])->defaultSort('start')
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('full')
                    ->beforeFormFilled(function (array $data, RelationManager $livewire) {
                        $data['mountedTableActionData.request_id'] = $livewire->ownerRecord->id;
                        $data['contact_id'] = $livewire->ownerRecord->contact_id;

                        return $data;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('full'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
    
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery();
    }
}
