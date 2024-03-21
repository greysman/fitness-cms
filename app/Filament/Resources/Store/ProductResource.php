<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\Store\ProductResource\Pages;
use App\Filament\Resources\Store\ProductResource\RelationManagers;
use App\Models\Store\Category;
use App\Models\Store\Product;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.store.label');
    }

    public static function getModelLabel(): string
    {
        return __('products.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('products.pluralLabel');
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
                'sm' => 8,
                'lg' => null,
            ]);
                // Forms\Components\Textarea::make('additional_data'),
    }


    public static function getPrimaryColumnSchema()
    {
        return [
            Tabs::make('tabs')
                ->tabs([
                    Tab::make('general')
                        ->label(__('products.form.tabs.general.label'))
                        ->schema([
                            TitleWithSlugInput::make(
                                fieldTitle: 'title',
                                fieldSlug: 'slug',
                                titlePlaceholder: __('products.form.title.label'),
                                urlVisitLinkLabel: __('filament-pages::filament-pages.filament.form.slug.visit_link.label'),
                            )->columnSpanFull(),

                            Group::make([
                                TextInput::make('price')
                                    ->required()
                                    ->label(__('products.form.price.label'))
                                    ->suffix(__('products.form.price.suffix')),
                            ])->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ]),

                            RichEditor::make('description')
                                ->label(__('products.form.description.label'))
                                ->maxLength(65535),
                            
                            Group::make([
                                Select::make('type_id')
                                    ->label(__('products.form.type.label'))
                                    ->options(Product::types())
                                    ->default(Product::TYPE_SUBSCRIPTION),
                                Toggle::make('subtract')
                                    ->label(__('products.form.subtract.label')),
                            ])
                        ]),
                    Tab::make('data')
                        ->label(__('products.form.tabs.data.label'))
                        ->schema([
                            CheckboxList::make('categories')
                                ->label(__('products.form.categories.label'))
                                ->required()
                                ->relationship('categories', 'title')
                                ->options(Category::list())
                                ->reactive(),
                            Select::make('category_id')
                                ->label(__('products.form.main_category.label'))
                                ->required()
                                ->options(function (\Closure $get) {
                                    $availableCategories = $get('categories');
                                    return Category::whereIn('id', $availableCategories)->pluck('title', 'id')->toArray();
                                }),
                            TextInput::make('sku')
                                ->label(__('products.form.sku.label'))
                                ->maxLength(255),
                        ]),
                    Tab::make('additional')
                        ->label(__('products.form.tabs.additional.label'))
                        ->statePath('additional_data')
                        ->schema([
                            TextInput::make('subtitle')
                                ->label(__('products.form.additional_data.subtitle.label'))
                                ->maxLength(50),
                            Fieldset::make('fieldset_conditions')
                                ->label(__('products.form.additional_data.fieldsets.conditions.label'))
                                ->schema([
                                    Forms\Components\TextInput::make('days')
                                        ->label(__('products.form.additional_data.days.label'))
                                        ->hint(__('products.form.additional_data.days.hint'))
                                        ->helperText(__('products.form.additional_data.days.helperText'))
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('period_text')
                                        ->label(__('products.form.additional_data.period_text.label'))
                                        ->hint(__('products.form.additional_data.period_text.hint'))
                                        ->helperText(__('products.form.additional_data.period_text.helperText')),
                                    Forms\Components\TextInput::make('trainings_count')
                                        ->label(__('products.form.additional_data.trainings_count.label'))
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('duration')
                                        ->label(__('products.form.additional_data.duration.label'))
                                        ->hint(__('products.form.additional_data.duration.hint'))
                                        ->helperText(__('products.form.additional_data.duration.helperText'))
                                        ->numeric()
                                        ->required(),
                                ]),

                            Fieldset::make('fieldset_publishing')
                                ->label(__('products.form.additional_data.fieldsets.publishing.label'))
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            Forms\Components\DateTimePicker::make('available_from')
                                                ->label(__('products.form.additional_data.available_from.label'))
                                                    ->default(Carbon::now()),
                                            Forms\Components\DateTimePicker::make('available_to')
                                                ->label(__('products.form.additional_data.available_to.label'))
                                                    ->helperText(__('products.form.additional_data.available_to.helperText')),
                                        ]),
                                ]),
                            Fieldset::make('fieldset_display')
                                ->label(__('products.form.additional_data.fieldsets.display.label'))
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            TextInput::make('button_link')
                                                ->label(__('products.form.additional_data.button.link.label')),
                                            TextInput::make('button_text')
                                                ->label(__('products.form.additional_data.button.text.label'))
                                                ->default(__('products.form.additional_data.button.text.default')),
                                        ])
                                ])
                        ]),
                    Tab::make('images')
                        ->label(__('products.form.tabs.images.label'))
                        ->schema([
                            Repeater::make('images')
                                ->label(false)
                                ->relationship('images')
                                ->minItems(0)
                                ->defaultItems(0)
                                ->columnSpan(2)
                                ->columns(2)
                                ->createItemButtonLabel(__('products.images.createButton.text'))
                                ->schema([
                                    FileUpload::make('url')
                                        ->label(__('products.images.form.image.label'))
                                        ->directory('products')
                                        ->disk('public')
                                        ->required(),
                                    TextInput::make('order')
                                        ->label(__('products.images.form.order.label'))
                                        ->default(0)
                                        ->numeric(),
                                ]),
                        ]),
                ])
        ];
    }


    public static function getSecondaryColumnSchema()
    {
        return Card::make([
            FileUpload::make('image_url')
                ->directory('products')
                ->disk('public')
                ->label(__('products.form.image.label')),
            TextInput::make('order')
                ->label(__('products.form.order.label'))
                ->numeric()
                ->default(0)
                ->required(),
            Toggle::make('active')
                ->label(__('products.form.active.label')),
            Toggle::make('published')
                ->label(__('products.form.published.label')),
        ])->columnSpan(['lg' => 2]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ImageColumn::make('image_url')
                    ->disk('public')    
                    ->label(__('products.table.image'))
                    ->circular()
                    ->size(80),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('products.table.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('products.table.price.label'))
                    ->suffix(__('products.table.price.suffix'))
                    ->sortable(),
                ToggleColumn::make('active')
                    ->label(__('products.table.active'))
                    ->sortable(),
                ToggleColumn::make('published')
                    ->label(__('products.table.published'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('products.table.order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('products.table.type'))
                    ->sortable(),
                // Tables\Columns\TextColumn::make('slug')
                //     ->label(__('products.table.slug')),
                Tables\Columns\TextColumn::make('sku')
                    ->label(__('products.table.sku'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('mainCategory.title')
                    ->label(__('products.table.category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('products.table.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('products.table.editor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('products.table.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('products.table.updated_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('products.table.deleted_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('viewed')
                    ->label(__('products.table.viewed'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->label(__('products.filters.active.label'))
                    ->options([
                        0 => __('products.filters.active.options.inactive'),
                        1 => __('products.filters.active.options.active')
                    ]),
                SelectFilter::make('published')
                    ->label(__('products.filters.published.label'))
                    ->options([
                        0 => __('products.filters.published.options.unpublished'),
                        1 => __('products.filters.published.options.published'),
                    ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
