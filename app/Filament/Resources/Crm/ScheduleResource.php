<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\ScheduleResource\Pages;
use App\Filament\Resources\Crm\ScheduleResource\RelationManagers;
use App\Forms\Components\UserSelect;
use App\Models\Cms\Gym;
use App\Models\Crm\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 6;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.crm.label');
    }

    public static function getModelLabel(): string 
    {
        return __('schedules.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('schedules.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getForm());
    }

    public static function getForm()
    {
        return [
            Group::make()
                ->columnSpanFull()
                ->schema([
                    Group::make()
                        ->schema([
                            static::getPrimaryColumnSchema(),
                        ])
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 'full',
                            'lg' => 6
                        ]),
                    static::getSecondaryColumnSchema(),
                ])->columns([
                    'sm' => 8,
                    'lg' => null,
                ]),
        ];
        
    }

    protected static function getPrimaryColumnSchema()
    {
        return Card::make([
            RadioButton::make('gym_id')
                ->label(__('schedules.form.gym.label'))
                ->options(Gym::list())
                ->required()
                ->columnSpanFull()
                ->columns([
                    'lg' => 2,
                    'md' => 2,
                    'sm' => 1,
                ])
                ->reactive(),
            Forms\Components\DateTimePicker::make('date')
                ->label(__('schedules.form.date.label'))
                ->hint(__('schedules.form.date.hintText'))
                ->required()
                ->withoutSeconds()
                ->disabled(function (Closure $get) {
                    return $get('gym_id') == null;
                })
                ->afterStateUpdated(function (Closure $get, Closure $set, $state) {
                    $date = Carbon::parse($state);
                    if (!$get('date_end') || ($get('date_end') && $date->gte(Carbon::parse($get('date_end'))))) {
                        $set('date_end', $date->addHour()->toDateTimeString());
                    }
                })
                ->rules([
                    function(Closure $get) {
                        return function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('active') && $get('gym_id') && $get('date') && $value) {
                                $schedulesQuery = Schedule::with(['trainer'])
                                    ->active()
                                    ->where('gym_id', $get('gym_id'))
                                    ->where([
                                        ['date', '<=', Carbon::parse($value)],
                                        ['date_end', '>', Carbon::parse($value)],
                                    ]);

                                if ($get('id')) {
                                    $schedulesQuery->where('id', '<>', $get('id'));
                                }

                                $schedules = $schedulesQuery->count();

                                if ($schedules) {
                                    $fail('Время начала накладывается на расписание другого тренера');
                                }
                            }
                        };
                    }
                ])
                ->reactive(),
            Forms\Components\DateTimePicker::make('date_end')
                ->label(__('schedules.form.date_end.label'))
                ->hint(__('schedules.form.date_end.hintText'))
                ->required()
                ->withoutSeconds()
                ->disabled(function (Closure $get) {
                    return $get('date') == null;
                })
                ->minDate(function (Closure $get) {
                    return Carbon::parse($get('date'))->addHour();
                })
                ->rules([
                    function(Closure $get) {
                        return function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('active') && $get('gym_id') && $get('date') && $value) {
                                $schedules = Schedule::with(['trainer'])
                                    ->active()
                                    ->where('gym_id', $get('gym_id'))
                                    ->where([
                                        ['date', '<', Carbon::parse($value)],
                                        ['date_end', '>', Carbon::parse($value)],
                                    ])
                                    ->count();
                                if ($schedules) {
                                    $fail('Время окончания накладывается на расписание другого тренера');
                                }
                            }
                        };
                    }
                ])
                ->reactive(),
            UserSelect::make('trainer_id')
                ->label(__('schedules.form.trainer.label'))
                ->required()
                ->options(function ($component) {
                    $items = User::whereHas('trainer')
                        ->active()
                        ->get();
        
                    return $items->mapWithKeys(function ($item) use ($component) {
                        return [$item->getKey() => $component->getCleanOptionString($item)];
                    })->toArray();
                })
                ->getSearchResultsUsing(function ($component, string $search) {

                    $items = User::whereHas('trainer')
                        ->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('surname', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('phone', 'like', "%$search%");
                        })
                        ->limit($component->queryLimit)
                        ->get();
        
                    return $items->mapWithKeys(function ($item) use ($component) {
                        return [$item->getKey() => $component->getCleanOptionString($item)];
                    })->toArray();
                }),
            Forms\Components\TextInput::make('title')
                ->label(__('schedules.form.title.label'))
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\Textarea::make('description')
                ->label(__('schedules.form.description.label'))
                ->maxLength(65535)
                ->columnSpanFull(),
        ])->columns([
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
        ]);
    }

    protected static function getSecondaryColumnSchema()
    {
        return Card::make([
            Toggle::make('active')
                ->label(__('schedules.form.active.label'))
                ->default(true),
        ])
        ->columnSpan(['lg' => 2]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('schedules.table.date'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_end')
                    ->label(__('schedules.table.date_end'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gym.title')
                    ->label(__('schedules.table.gym')),
                Tables\Columns\TextColumn::make('trainer.fullname')
                    ->label(__('schedules.table.trainer'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('schedules.table.title'))
                    ->limit(50),
                ToggleColumn::make('active')
                    ->label(__('schedules.table.active'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('schedules.table.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('schedules.table.editor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('schedules.table.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('schedules.table.updated_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('schedules.table.deleted_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('gym_id')
                    ->label(__('schedules.table.filters.gym.label'))
                    ->options(Gym::all()->pluck('title', 'id')),
                SelectFilter::make('trainer_id')
                    ->label(__('schedules.table.filters.trainer.label'))
                    ->options(User::whereHas('trainer')->get()->pluck('fullname', 'id')),
                SelectFilter::make('active')
                    ->label(__('schedules.table.filters.active.label'))
                    ->options([
                        0 => __('schedules.table.filters.active.options.inactive'),
                        1 => __('schedules.table.filters.active.options.active'),
                    ]),
                Filter::make('date')
                    ->label(false)
                    ->form([
                        Fieldset::make(__('schedules.table.filters.date.label'))
                            ->schema([
                                DatePicker::make('date_from')
                                    ->label(__('schedules.table.filters.date.from')),
                                DatePicker::make('date_to')
                                    ->label(__('schedules.table.filters.date.to')),
                            ])
                            ->columns(1)
                            ->inlineLabel(),
                        ])
                        ->query(function (Builder $query, array $data) {
                            return $query
                                ->when(
                                    $data['date_from'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date)
                                )
                                ->when(
                                    $data['date_to'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date)
                                );
                        }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
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
