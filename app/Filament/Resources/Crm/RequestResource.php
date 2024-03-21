<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\RequestResource\Pages;
use App\Filament\Resources\Crm\RequestResource\RelationManagers;
use App\Filament\Resources\Crm\RequestResource\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Crm\RequestResource\RelationManagers\OffersRelationManager;
use App\Forms\Components\UserSelect;
use App\Models\Cms\Gym;
use App\Models\Crm\Contact;
use App\Models\Crm\Request;
use Carbon\Carbon;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use KoalaFacade\FilamentAlertBox\Forms\Components\AlertBox;
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-o-filter';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::newCount() ?: false;
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.crm.label');
    }

    public static function getLabel(): string 
    {
        return __('requests.label');
    }

    public static function getModelLabel(): string 
    {
        return __('requests.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('requests.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'sm' => 9,
                    'lg' => null,
                ])
                ->schema([
                    Group::make(static::getPrimaryColumnSchema())
                        ->columnSpan([
                            'sm' => 'full',
                            'md' => 'full',
                            'lg' => 6
                        ]),
                    Group::make()
                        ->schema(static::getSecondaryColumnSchema())
                        ->columnSpan(['lg' => 3]),
                    ])
                ]);
    }

    protected static function getPrimaryColumnSchema()
    {
        return [
            Card::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label(__('requests.form.title.label'))
                        ->required()
                        ->maxLength(255),
                    Grid::make()
                        ->schema([
                            Forms\Components\DateTimePicker::make('expected_close_date')
                                ->label(__('requests.form.expected_close_date.label'))
                                ->default(function () {
                                    return Carbon::today()->addDay()->addHours(18);
                                }),
                            Forms\Components\TextInput::make('expected_profit')
                                ->label(__('requests.form.expected_profit.label'))
                                ->default(0)
                                ->suffix(__('requests.form.expected_profit.suffix'))
                                ->required(),
                        ])
                    
                ]),
            Card::make([
                Forms\Components\Textarea::make('comment')
                    ->label(__('requests.form.comment.label'))
                    ->maxLength(65535),
            ]),
            Card::make([
                Forms\Components\Textarea::make('lost_reason')
                    ->maxLength(65535),
            ])->visible(fn (Closure $get): bool => $get('stage_id') == Request::STAGE_LOST)
        ];
    }

    protected static function getSecondaryColumnSchema()
    {
        return [
            AlertBox::make()
                ->label(function (Closure $get) {
                    return Request::stages()[$get('stage_id')];
                })
                ->visible(function (Closure $get) {
                    return in_array($get('stage_id'), [Request::STAGE_NEW, Request::STAGE_EXPLORE, Request::STAGE_OFFER]);
                })
                ->info()
                ->hiddenOn('create'),
            AlertBox::make()
                ->label(function (Closure $get) {
                    return Request::stages()[$get('stage_id')];
                })
                ->visible(function (Closure $get) {
                    return $get('stage_id') == Request::STAGE_LOST;
                })
                ->danger()
                ->hiddenOn('create'),
            AlertBox::make()
                ->label(function (Closure $get) {
                    return Request::stages()[$get('stage_id')];
                })
                ->visible(function (Closure $get) {
                    return $get('stage_id') == Request::STAGE_WIN;
                })
                ->success()
                ->hiddenOn('create'),
            Card::make([
                Hidden::make('status_id'),
                Hidden::make('stage_id'),
                UserSelect::make('contact_id')
                    ->label(__('requests.form.contact.label'))
                    ->setSearchModel(Contact::class)
                    ->helperText(function (\Closure $get) {
                        if ($get('contact_id')) {
                            return new HtmlString('<a href="' . route('filament.resources.crm/contacts.view', [$get('contact_id')]) . '" target="_blank">Просмотреть контакт</a>');
                        }
                    })
                    ->reactive()
                    ->required(),
                UserSelect::make('responsible_id')
                    ->label(__('requests.form.responsible.label')),
                RadioButton::make('gym_id')
                    ->label(__('requests.form.gym.label'))
                    ->options(Gym::list())
                    ->required(),
                Select::make('source_id')
                    ->label(__('requests.form.source.label'))
                    ->options(Request::sources()),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
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
            ->defaultSort('created_at', 'desc')
            ->actions(static::getTableActions())
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\TextColumn::make('title')
                ->label(__('requests.table.title'))
                ->searchable(),
            // BadgeColumn::make('status')
            //     ->label(__('requests.table.status'))
            //     ->enum(Request::statuses())
            //     ->colors([

            //     ]),
            Tables\Columns\TextColumn::make('contact.name')
                ->label(__('requests.table.contact'))
                ->url(function ($record) {
                    return route('filament.resources.crm/contacts.edit', [$record->contact->id]);
                }, true)
                ->sortable()
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
                ->suffix(__('requests.table.expected_profit.suffix')),
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
        ];
    }
    
    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
            OffersRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RequestResource\Widgets\Overdue::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'view' => Pages\ViewRequest::route('/{record}'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
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
