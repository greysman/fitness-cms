<?php

namespace App\Filament\Widgets;

use App\Models\Crm\Contact;
use App\Models\Crm\Signup;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class StatsOverview extends BaseWidget 
{
    protected static ?int $sort = 2;


    protected function getCards(): array
    {
        ;
        return [
            static::getContactsStatsCard(),
            static::getProfitStatsCard(),
            static::getVisitsStatsCard(),
        ];
    }

    protected function getContactsStatsCard(): Component
    {
        $lastPeriodValue = Contact::whereDate('created_at', '>=', Carbon::today()->subDays(13))
            ->whereDate('created_at', '<=', Carbon::today()->subDays(6))
            ->active()
            ->count();
        $currentPeriodValue = Contact::whereDate('created_at', '>=', Carbon::today()->subDays(6))
            ->active()
            ->count();

        $contactsPerDay = Contact::whereDate('created_at', '>=', Carbon::now()->subDays(6))
            ->active()
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as "contacts"'),
            ])
            ->pluck('contacts', 'date')
            ->toArray();

        $period = CarbonPeriod::create(Carbon::today()->subDays(6), Carbon::now());
        $chart = [];
        foreach ($period as $day) {
            $date = $day->toDateString();
            $chart[$date] = isset($contactsPerDay[$date]) ? $contactsPerDay[$date] : 0;
        }

        return $this->getOverviewCard(
            __('stats-overview.cards.contacts.label'),
            'primary',
            $lastPeriodValue,
            $currentPeriodValue,
            $chart
        );
    }


    protected function getProfitStatsCard(): Component
    {
        return Card::make(__('stats-overview.cards.income.label'), '0' . __('stats-overview.cards.income.suffix'))
            // ->description('0% increase')
            ->description('')
            ->descriptionIcon('heroicon-s-trending-up')
            ->chart([0, 0, 0, 0, 0, 0, 0])
            ->color('danger');
    }


    protected function getVisitsStatsCard(): Component
    {
        $lastPeriodValue = Signup::whereIn('status_id', [Signup::STATUS_FINISHED, Signup::STATUS_PROCESSING])
            ->whereDate('date', '>=', Carbon::today()->subDays(13))
            ->whereDate('date', '<=', Carbon::today()->subDays(6))
            ->count();
        $currentPeriodValue = Signup::whereIn('status_id', [Signup::STATUS_FINISHED, Signup::STATUS_PROCESSING])
            ->whereDate('date', '>=', Carbon::today()->subDays(6))
            ->count();

            $visitsPerDay = Signup::whereIn('status_id', [Signup::STATUS_FINISHED, Signup::STATUS_PROCESSING])
            ->whereDate('date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('Date(date) as day'),
                DB::raw('COUNT(*) as "visits"'),
            ])
            ->pluck('visits', 'day')
            ->toArray();

        $period = CarbonPeriod::create(Carbon::today()->subDays(6), Carbon::now());
        $chart = [];
        foreach ($period as $day) {
            $date = $day->toDateString();
            $chart[$date] = isset($visitsPerDay[$date]) ? $visitsPerDay[$date] : 0;
        }

        return $this->getOverviewCard(
            __('stats-overview.cards.visits.label'),
            'success',
            $lastPeriodValue,
            $currentPeriodValue,
            $chart
        );
    }

    protected function getOverviewCard(string $title, string $color, $lastPeriodValue, $currentPeriodValue, $chart): Component
    {
        $percentageIncrease = $lastPeriodValue 
            ? (($currentPeriodValue - $lastPeriodValue)/$lastPeriodValue) * 100
            : 0;

        if ($percentageIncrease == 0) {
            $icon = 'heroicon-s-minus';
            $description = '';
        } else if ($percentageIncrease > 0) {
            $icon = 'heroicon-s-trending-up';
            $description = number_format($percentageIncrease, 1) . '% ' . __('stats-overview.common.increase');
        } else {
            $icon = 'heroicon-s-trending-down';
            $description = number_format($percentageIncrease * -1, 1) . '% ' . __('stats-overview.common.descrease');
        }

        return Card::make($title, $currentPeriodValue)
            ->description($description)
            ->descriptionIcon($icon)
            ->color($color)
            ->chart(array_values($chart));
    }
}