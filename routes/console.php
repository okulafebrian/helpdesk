<?php

use App\Enums\SlaStatus;
use App\Models\Ticket;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Ticket::where('response_sla_status', SlaStatus::RUNNING)
        ->where('response_due_at', '<', now())
        ->update([
            'response_sla_status' => SlaStatus::BREACHED,
        ]);
    
    Ticket::where('resolution_sla_status', SlaStatus::RUNNING)
        ->where('resolution_due_at', '<', now())
        ->update([
            'resolution_sla_status' => SlaStatus::BREACHED,
        ]);
})->everyMinute();
