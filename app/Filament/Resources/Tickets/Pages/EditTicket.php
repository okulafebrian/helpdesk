<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\SlaStatus;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTitle(): string
    {
        return $this->record->title;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] == TicketStatus::OPEN && !$this->record->first_response_at) {
            $responseSlaStatus = now() > $this->record->response_due_at
                ? SlaStatus::BREACHED 
                : SlaStatus::MET;

            $data['first_response_at'] = now();
            $data['response_sla_status'] = $responseSlaStatus;
        } else if ($data['status'] == TicketStatus::RESOLVED && !$this->record->resolved_at) {
            $resolutionSlaStatus = now() > $this->record->resolution_due_at 
                ? SlaStatus::BREACHED
                : SlaStatus::MET;

            $data['resolved_at'] = now();
            $data['resolution_sla_status'] = $resolutionSlaStatus;
        }

        return $data;
    }

    #[On('comment-created')]
    public function refreshRecord(): void
    {
        $this->record->refresh();
        $this->fillForm();
    }
}
