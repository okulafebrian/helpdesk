<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KPISummary extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $tickets = Ticket::whereNotNull('first_response_at')->get();

        $averageSeconds = $tickets->avg(function ($ticket) {
            return Carbon::parse($ticket->created_at)
                ->diffInSeconds($ticket->first_response_at);
        });
        $averageMinutes = $averageSeconds / 60;
        $averageHours = $averageMinutes / 60;

        return [
            Stat::make('Active Tickets', 250)
                ->description(12 . ' from last week')
                ->descriptionIcon(Heroicon::ArrowUp, IconPosition::Before)
                ->descriptionColor('danger'),
            Stat::make('SLA Breach Rate', 8.4 . '%')
                ->description(1.2 . '% improvement')
                ->descriptionIcon(Heroicon::ArrowDown, IconPosition::Before)
                ->descriptionColor('success'),
            Stat::make('Avg. First Response', number_format($averageHours, 2) . 'h')
                ->description('within target')
                ->descriptionIcon(Heroicon::ArrowDown, IconPosition::Before)
                ->descriptionColor('success'),
            Stat::make('Resolved Today', 34)
                ->description(6 . ' vs yesterday')
                ->descriptionIcon(Heroicon::ArrowUp, IconPosition::Before)
                ->descriptionColor('success'),
        ];
    }
}
