<?php

namespace App\Filament\Resources\Crm;

use App\Filament\Resources\Crm\TrainerReportResource\Pages;
use App\Filament\Resources\Crm\TrainerReportResource\RelationManagers;
use App\Models\Crm\TrainerReport;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainerReportResource extends Resource
{
    protected static ?string $model = TrainerReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): string
    {
        return __('base.navigation_groups.reports.label');
    }

    public static function getLabel(): string 
    {
        return __('requests.label');
    }

    public static function getModelLabel(): string 
    {
        return 'Отчет';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Отчет по тренерам';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTrainerReports::route('/'),
        ];
    }    
}
