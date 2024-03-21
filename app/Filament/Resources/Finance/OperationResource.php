<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\OperationResource\Pages;
use App\Filament\Resources\Finance\OperationResource\RelationManagers;
use App\Models\Expenditure;
use App\Models\Operation;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Null_;

class OperationResource extends Resource
{
    protected static ?string $model = Operation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 0;

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.finance.label');
    }

    public static function getModelLabel(): string
    {
        return __('operations.modelLabel');
    }

    public static function getPluralLabel(): ?string
    {
        return __('operations.pluralLabel');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('common')
                        ->label(__('operations.wizard.common'))
                        ->schema(self::getCommonStepSchema()),
                    Wizard\Step::make('contact')
                        ->label(__('operations.wizard.contact'))
                        ->schema(self::getContactStepSchema())
                        ->hidden(fn (Closure $get) => !self::canShowContactStep($get('expenditure_id'))),
                    Wizard\Step::make('items')
                        ->label(__('operations.wizard.items'))
                        ->schema(self::getItemsStepSchema())
                        ->hidden(fn (Closure $get) => !self::canShowItemsStep($get('expenditure_id'))),
                ])
                ->columnSpanFull(),   
            ]);
    }

    protected static function getCommonStepSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Group::make([
                        Forms\Components\TextInput::make('uid')
                            ->label(__('operations.fields.uid'))
                            ->required()
                            ->unique('operations', ignoreRecord: true)
                            ->default(fn () => uniqid(more_entropy: true))
                            ->maxLength(50),
            
                        Select::make('expenditure_id')
                            ->label(__('operations.fields.expenditure'))
                            ->relationship('expenditure', 'title')
                            ->hiddenOn('edit')
                            ->reactive()
                            ->required(),
            
                        Forms\Components\TextInput::make('total_amount')
                            ->label(__('operations.fields.total_amount'))
                            ->suffix('RUB')
                            ->default(0),
                    ]),
                    Group::make([
                        Forms\Components\TextInput::make('hash')
                            ->label(__('operations.fields.hash'))
                            ->maxLength(255),
                    ]),
                ]),

            Forms\Components\Textarea::make('comment')
                ->label(__('operations.fields.comment'))
                ->columnSpanFull()
                ->maxLength(65535),
        ];
    }

    protected static function getContactStepSchema(): array
    {
        return [
            Forms\Components\TextInput::make('contact_id'),
        ];
    }

    protected static function getItemsStepSchema(): array
    {
        return [
            Fieldset::make('fieldset_discount')
                ->label(__('operations.fieldsets.discount.label'))
                ->schema([
                    Select::make('discount_type')
                        ->label(__('operations.fields.discount_type'))
                        ->options(Operation::discountTypes())
                        ->default(Operation::DISCOUNT_TYPE_PERCENT),
                    Forms\Components\TextInput::make('discount')
                        ->label(__('operations.fields.discount'))
                        ->default(0)
                        ->numeric(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')
                    ->label(__('operations.fields.uid'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('expenditure.title')
                    ->label(__('operations.fields.expenditure'))
                    ->sortable(),
                BadgeColumn::make('discount')
                    ->label(__('operations.fields.discount')),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('operations.fields.total_amount')),
                Tables\Columns\TextColumn::make('contact.name')
                    ->label(__('operations.fields.contact'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('operations.fields.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('operations.fields.editor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('operations.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('operations.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
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

    protected static function getExpenditure(int $expenditureID): Model | bool
    {
        return Cache::remember('expenditure_' . $expenditureID, 600, function () use ($expenditureID) {
            return Expenditure::find($expenditureID);
        }) ?: false;
    }

    protected static function canShowContactStep(int | null $expenditureID): bool
    {
        return $expenditureID 
            ? (!($expenditure = self::getExpenditure($expenditureID)) ?: !!$expenditure->has_contact)
            : false;
    }

    protected static function canShowItemsStep(int | null $expenditureID): bool
    {
        return $expenditureID 
            ? (!($expenditure = self::getExpenditure($expenditureID)) ?: !!$expenditure->has_items)
            : false;
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
            'index' => Pages\ListOperations::route('/'),
            'create' => Pages\CreateOperation::route('/create'),
            'edit' => Pages\EditOperation::route('/{record}/edit'),
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
