<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Crm\RequestResource;
use App\Models\Cms\Gym;
use App\Models\Crm\Request;
use Closure;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RequestWidget extends BaseWidget
{
    protected static ?string $model = Request::class;

    protected int | string | array $columnSpan = 'full';

    public $tableRecordsPerPage = 6;

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return true;
    }

    protected function getTableQuery(): Builder
    {
        return Request::query()->new();
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Request $record): string => route('filament.resources.crm/requests.edit', ['record' => $record->id]);
    }

    protected function getTableHeading(): string
    {
        return __('requests.widgets.new.tableHeading') . ' - ' . $this->getTableQuery()->count();
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
