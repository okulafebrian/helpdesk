<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\SlaStatus;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do {
            $number = 'TC-' . random_int(100000, 999999);
        } while (Ticket::where('number', $number)->exists());

        $data['number'] = $number;

        $priority = $data['priority'];
        $slaPolicy = SlaPolicy::where('priority', $priority)->first();

        $responseTime = $slaPolicy->response_hrs;
        $resolutionTime = $slaPolicy->resolution_hrs;


        $data['response_due_at'] = $this->addBusinessHours($responseTime);
        $data['resolution_due_at'] = $this->addBusinessHours($resolutionTime);

        $data['response_sla_status'] = SlaStatus::RUNNING;
        $data['resolution_sla_status'] = SlaStatus::RUNNING;

        return $data;
    }

    private function addBusinessHours(int $hours)
    {
        $remaining = $hours * 60;
        $cursor = $this->snapToNextBusinessTime(now());

        while ($remaining > 0) {
            if ($cursor->isWeekend()) {
                $cursor = $this->nextDayStart($cursor);
                continue;
            }

            $dayEnd = $cursor->copy()->setTime(17, 0);
            $minutesLeft = (int) max(0, $cursor->diffInMinutes($dayEnd));

            if ($remaining <= $minutesLeft) {
                $cursor->addMinutes($remaining);
                $remaining = 0;
            } else {
                $remaining -= $minutesLeft;
                $cursor = $this->nextDayStart($cursor);
            }
        }
     
        return $cursor;
    }

    private function snapToNextBusinessTime(Carbon $date)
    {
        $cursor = $date->copy();
        
        while ($cursor->isWeekend()) {
            $cursor->startOfDay()->addDay();
        }

        $dayStart = $cursor->copy()->setTime(8, 0);
        $dayEnd = $cursor->copy()->setTime(17, 0);

        if ($cursor->lt($dayStart)) {
            return $dayStart;
        }

        if ($cursor->gte($dayEnd)) {
            return $this->snapToNextBusinessTime($cursor->startOfDay()->addDay());
        }

        return $cursor;
    }

    private function nextDayStart(Carbon $date)
    {
        $cursor = $date->copy()->startOfDay()->addDay();

        while ($cursor->isWeekend()) {
            $cursor->addDay();
        }

        $dayStart = $cursor->copy()->setTime(8, 0);

        if ($cursor->lt($dayStart)) {
            return $dayStart;
        }

        return $cursor;
    }
}
