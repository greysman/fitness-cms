<?php

namespace App\Filament\Resources\Crm\RequestResource\RelationManagers;

use App\Forms\Components\ProductSelect;
use App\Models\Crm\Offer;
use App\Models\Store\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class OffersRelationManager extends RelationManager
{
    protected static string $relationship = 'offers';

    protected static ?string $recordTitleAttribute = 'product.title';

    public static function getModelLabel(): string 
    {
        return __('requests.relations.offers.label');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('requests.relations.offers.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    })
                    ->columnSpanFull()
                    ->reactive()
                    ->required(),
                Grid::make(4)
                    ->schema([
                        TextInput::make('discount_value')
                            ->label(__('requests.relations.offers.discount.label'))
                            ->default(0)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(function (Closure $get) {
                                if (!$get('product_id')) return null;
                                $value = $get('discount_type') == Offer::DISCOUNT_TYPE_PERCENT 
                                    ? 100
                                    : Product::find($get('product_id'))->price;
                                // dd($value);
                                return $value;
                            })
                            ->columnSpan(1)
                            ->reactive(),
                        Select::make('discount_type')
                            ->label(__('requests.relations.offers.discount.type'))
                            ->options(Offer::discountTypes())
                            ->columnSpan(1)
                            ->default(Offer::DISCOUNT_TYPE_PERCENT)
                            ->reactive(),
                        Select::make('status_id')
                            ->label(__('requests.relations.offers.status.label'))
                            ->options(Offer::statuses())
                            ->default(Offer::STATUS_DRAFT)
                            ->columnSpan(2)
                            ->required(),
                    ]),
                Placeholder::make('amount')
                    ->label(__('requests.relations.offers.amount.label'))
                    ->visible(function (Closure $get) {
                        return !!$get('product_id');
                    })
                    ->content(function (Closure $get) {
                        if ($get('product_id')) {
                            $product = Product::find($get('product_id'));
                            $amount = $product->price;
                            if ($get('discount_value')) {
                                $amount = $get('discount_type') == Offer::DISCOUNT_TYPE_PERCENT
                                    ? $amount - $amount * ($get('discount_value') / 100)
                                    : $amount - $get('discount_value');
                            }
                            return new HtmlString("<strong>$amount</strong>");
                        }
                    })
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product.image_url')
                    ->disk('public')    
                    ->label(__('products.table.image'))
                    ->circular()
                    ->size(80),
                Tables\Columns\TextColumn::make('product.title')
                    ->label(__('products.table.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.price')
                    ->label(__('products.table.price.label'))
                    ->suffix(__('products.table.price.suffix'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_value')
                    ->label(__('requests.relations.offers.discount.label'))
                    ->suffix(function($record) {
                        return $record->discount_type == Offer::DISCOUNT_TYPE_PERCENT
                            ? '%'
                            : __('products.table.price.suffix');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('requests.relations.offers.amount.label'))
                    ->suffix(__('products.table.price.suffix')),
                BadgeColumn::make('status_id')
                    ->label(__('requests.relations.offers.status.label'))
                    ->enum(Offer::statuses())
                    ->colors([
                        'secondary' => Offer::STATUS_DRAFT,
                        'primary' => Offer::STATUS_ACTIVE,
                        'success' => Offer::STATUS_APPROVED,
                        'danger' => Offer::STATUS_CANCELED,
                    ])
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label(__('requests.relations.offers.actions.activate'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->status_id = Offer::STATUS_ACTIVE;
                        $record->save();
                    })
                    ->visible(function ($record) {
                        return $record->status_id == Offer::STATUS_DRAFT;
                    }),
                Tables\Actions\Action::make('send_sms')
                    ->label(__('requests.relations.offers.actions.send_sms'))
                    ->icon('heroicon-o-annotation')
                    ->color('primary')
                    ->action(function ($record) {
                        Notification::make()
                            ->title('Настройки СМС шлюза некорректны')
                            ->icon('heroicon-o-x') 
                            ->iconColor('danger') 
                            ->send();
                    })
                    ->visible(function ($record) {
                        return $record->status_id == Offer::STATUS_ACTIVE;
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label(__('requests.relations.offers.actions.cancel'))
                    ->icon('heroicon-o-x')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->status_id = Offer::STATUS_CANCELED;
                        $record->save();
                    })
                    ->hidden(function ($record) {
                        return $record->status_id == Offer::STATUS_DRAFT;
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }    
    
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with('product')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
