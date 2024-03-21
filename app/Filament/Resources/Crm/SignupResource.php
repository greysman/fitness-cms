<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\SignupResource\Pages;
use App\Filament\Resources\Crm\SignupResource\RelationManagers;
use App\Forms\Components\ProductSelect;
use App\Forms\Components\UserSelect;
use App\Models\Cms\Gym;
use App\Models\Crm\Contact;
use App\Models\Crm\Signup;
use App\Models\Store\Product;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;
use Yepsua\Filament\Forms\Components\Rating;

class SignupResource extends Resource
{
    protected static ?string $model = Signup::class;

    protected static ?string $navigationIcon = 'heroicon-o-table';

    protected static ?int $navigationSort = 10;

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::newCount() ?: false;
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.crm.label');
    }

    public static function getModelLabel(): string 
    {
        return __('signups.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('signups.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema(static::getPrimaryColumnSchema())
                    ->columnSpan([
                        'sm' => 'full',
                        'md' => 'full',
                        'lg' => 6
                    ]),
                static::getSecondaryColumnSchema(),
            ])
            ->columns([
                'sm' => 9,
                'lg' => null,
            ]);
    }

    public static function getPrimaryColumnSchema()
    {
        return [
            Tabs::make(false)
                ->schema([
                    Tab::make('general')
                        ->label(__('signups.form.tabs.general.label'))
                        ->schema([
                            RadioButton::make('gym_id')
                                ->label(__('signups.form.gym.label'))
                                ->options(Gym::list())
                                ->required()
                                ->columns([
                                    'lg' => 2,
                                    'md' => 2,
                                    'sm' => 1,
                                ]),
                            Grid::make()
                                ->schema([
                                    UserSelect::make('trainer_id')
                                        ->label(__('signups.form.trainer.label'))
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
                                    UserSelect::make('responsible_id')
                                        ->label(__('signups.form.responsible.label')),
                                    ProductSelect::make('product_id')
                                        ->label(__('signups.form.product.label'))
                                        ->options(function($component) {
                                            $items = Product::subscription()->active()->get();
                                
                                            return $items->mapWithKeys(function ($item) use ($component) {
                                                return [$item->getKey() => $component->getCleanOptionString($item)];
                                            })->toArray();
                                        })
                                        ->getSearchResultsUsing(function ($component, string $search) {

                                            $items = Product::subscription()
                                                ->active()
                                                ->where(function ($query) use ($search) {
                                                    $query->where('title', 'like', "%$search%")
                                                        ->orWhere('sku', 'like', "%$search%");
                                                })
                                                ->limit($component->queryLimit)
                                                ->get();
                                
                                            return $items->mapWithKeys(function ($item) use ($component) {
                                                return [$item->getKey() => $component->getCleanOptionString($item)];
                                            })->toArray();
                                        })->columnSpanFull(),
                                    Forms\Components\DateTimePicker::make('date')
                                        ->label(__('signups.form.date.label'))
                                        ->withoutSeconds()
                                        ->required(),
                                    Forms\Components\TextInput::make('duration')
                                        ->label(__('signups.form.duration.label'))
                                        ->numeric()
                                        ->step(10)
                                        ->required(),
                                ]),
                            RichEditor::make('comment')
                                ->label(__('signups.form.comment.label'))
                                ->maxLength(65535),
                        ]),
                    Tab::make('review')
                        ->label(__('signups.form.tabs.review.label'))
                        ->hiddenOn('create')
                        ->schema(static::getCustomerReviewFormSchema()),
                ])
        ];
    }

    public static function getSecondaryColumnSchema()
    {
        return Card::make()
            ->schema([
                UserSelect::make('contact_id')
                    ->label(__('signups.form.contact.label'))
                    ->setSearchModel(Contact::class)
                    ->helperText(function (\Closure $get) {
                        if ($get('contact_id')) {
                            return new HtmlString('<a href="' . route('filament.resources.crm/contacts.view', [$get('contact_id')]) . '" target="_blank">Просмотреть контакт</a>');
                        }
                    })
                    ->reactive()
                    ->required(),

                Select::make('status_id')
                    ->label(__('signups.form.status.label'))
                    ->options(Signup::statuses())
                    ->default(Signup::STATUS_NEW)
                    ->disabled()
                    ->reactive(),

                Hidden::make('status_id'),
                
                DateTimePicker::make('start_time')
                    ->label(__('signups.form.start_time.label'))
                    // ->reactive()
                    ->withoutSeconds()
                    // ->minutesStep(5)
                    ->hiddenOn('create'),
                
                DateTimePicker::make('finish_time')
                    ->label(__('signups.form.finish_time.label'))
                    // ->reactive()
                    ->withoutSeconds()
                    // ->minutesStep(5)
                    ->hiddenOn('create'),
            ])
            ->columnSpan(['lg' => 3]);
    }


    public static function getCustomerReviewFormSchema()
    {
        return [
            Rating::make('rating')
                ->label(__('signups.form.rating.label'))
                ->options(__('signups.form.rating.options')),
            RichEditor::make('review')
                ->label(__('signups.form.review.label'))
                ->maxLength(65535),
        ];
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('signups.table.date'))
                    ->dateTime()
                    ->sortable(),
                BadgeColumn::make('status_id')
                    ->label(__('signups.table.status'))
                    ->enum(Signup::statuses())
                    ->colors(Signup::statusesColors())
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact.fullname')
                    ->label(__('signups.table.contact'))
                    ->sortable()
                    ->searchable(['name', 'surname']),
                Tables\Columns\TextColumn::make('gym.title')
                    ->label(__('signups.table.gym'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('trainer.name')
                    ->label(__('signups.table.trainer'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.title')
                    ->label(__('signups.table.product'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label(__('signups.table.duration.label'))
                    ->sortable()
                    ->suffix(__('signups.table.duration.suffix'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('signups.table.start_time'))
                    ->time()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('finish_time')
                    ->label(__('signups.table.finish_time'))
                    ->time()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->label(__('signups.table.responsible'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('signups.table.author'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('signups.table.editor'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('signups.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('signups.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('signups.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('gym_id')
                    ->label(__('signups.filters.gym.label'))
                    ->options(Gym::list()),
                SelectFilter::make('trainer_id')
                    ->label(__('signups.filters.trainer.label'))
                    ->options(User::whereHas('trainer')->get()->pluck('fullname', 'id')),
                SelectFilter::make('responsible_id')
                    ->label(__('signups.filters.responsible.label'))
                    ->options(User::all()->pluck('fullname', 'id')),
                SelectFilter::make('product_id')
                    ->label(__('signups.filters.product.label'))
                    ->options(Product::active()->subscription()->pluck('title', 'id')),
                SelectFilter::make('status_id')
                    ->multiple()
                    ->label(__('signups.filters.status.label'))
                    ->options(Signup::statuses())
                    ->default([Signup::STATUS_NEW, Signup::STATUS_PROCESSING]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->label(__('signups.actions.start.label'))
                    ->icon('heroicon-o-play')
                    ->action(function (Signup $record) {
                        $record->update([
                            'status_id' => Signup::STATUS_PROCESSING,
                            'start_time' => Carbon::now(),
                        ]);
                    })
                    ->visible(fn (Signup $record): bool => $record->status_id == Signup::STATUS_NEW),
                Tables\Actions\Action::make('stop')
                    ->label(__('signups.actions.stop.label'))
                    ->icon('heroicon-o-stop')
                    ->action(function (Signup $record, array $data) {
                        $record->update([
                            'status_id' => Signup::STATUS_FINISHED,
                            'finish_time' => Carbon::now(),
                            'rating' => (int) $data['rating'],
                            'review' => $data['review'],
                        ]);
                    })
                    ->form(static::getCustomerReviewFormSchema())
                    ->visible(fn (Signup $record): bool => $record->status_id == Signup::STATUS_PROCESSING),
                Tables\Actions\Action::make('cancel')
                    ->label(__('signups.actions.cancel.label'))
                    ->modalHeading(__('signups.actions.cancel.modal.heading'))
                    ->modalButton(__('signups.actions.cancel.modal.confirmButton'))
                    ->icon('heroicon-o-x')
                    ->color('danger')
                    ->form([
                        RichEditor::make('cancel_comment')
                            ->label(__('signups.actions.cancel.modal.comment.label'))
                            ->required(),
                    ])
                    ->action(function (Signup $record, array $data) {
                        $comment = empty($record->comment)
                            ? __('signups.actions.cancel.reasonHeader') . $data['cancel_comment']
                            : $record->comment . __('signups.actions.cancel.reasonHeader') . $data['cancel_comment'];

                        $record->update([
                            'status_id' => Signup::STATUS_CANCELED,
                            'comment' => $comment,
                        ]);
                    })
                    ->visible(fn (Signup $record): bool => $record->status_id == Signup::STATUS_NEW),
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
            'index' => Pages\ListSignups::route('/'),
            'create' => Pages\CreateSignup::route('/create'),
            'view' => Pages\ViewSignup::route('/{record}'),
            'edit' => Pages\EditSignup::route('/{record}/edit'),
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
