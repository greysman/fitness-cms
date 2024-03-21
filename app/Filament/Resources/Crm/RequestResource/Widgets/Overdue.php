<?php

namespace App\Filament\Resources\Crm\RequestResource\Widgets;

use App\Filament\Resources\Crm\RequestResource;
use App\Models\Cms\Gym;
use App\Models\Crm\Request;
use Closure;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class Overdue extends TableWidget
{
    protected static ?string $model = Request::class;

    protected int | string | array $columnSpan = 'full';

    public $tableRecordsPerPage = 5;

    public static function canView(): bool
    {
        return !!Request::overdueCount();
    }

    protected static ?int $sort = 5;

    protected function getTableQuery(): Builder
    {
        return Request::query()->overdue();
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Request $record): string => route('filament.resources.crm/requests.edit', ['record' => $record->id]);
    }

    protected function getTableHeading(): string
    {
        return __('requests.widgets.overdue.tableHeading') . ' - ' . $this->getTableQuery()->count();
    }

    protected function getTableColumns(): array
    {
        return RequestResource::getTableColumns();
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('responsible')
                ->label(__('requests.filters.responsible.value'))
                ->query(fn (Builder $query): Builder => $query->where('responsible_id', Filament::auth()->user()->id)),
            SelectFilter::make('gym_id')
                ->label(__('requests.filters.gym.label'))
                ->options(Gym::all()->pluck('title', 'id')),
            SelectFilter::make('source_id')
                ->label(__('requests.filters.source.label'))
                ->options(Request::sources()),
        ];
    }

    protected function getTableActions(): array
    {
        return RequestResource::getTableActions();
    }
}
