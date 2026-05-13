<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Enums\TicketStatus;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('assignee.name')
                    ->label('Assignee'),
                TextColumn::make('sla')
                    ->label('SLA')
                    ->badge()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("
                            CASE
                                WHEN first_response_at IS NOT NULL THEN resolution_due_at
                                ELSE response_due_at
                            END {$direction}
                        ");
                    })
                    ->getStateUsing(function ($record) {
                        $due = Carbon::parse(
                            $record->first_response_at
                                ? $record->resolution_due_at
                                : $record->response_due_at
                        );

                        $diff = now()->diffForHumans($due, Carbon::DIFF_ABSOLUTE, true);

                        return now()->gt($due) ? "-{$diff}" : "{$diff}";
                    })
                    ->color(function ($record) {
                        $slaStatus = $record->first_response_at
                            ? $record->resolution_sla_status
                            : $record->response_sla_status;

                        return $slaStatus->getColor();
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TicketStatus::class),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('Duplicate'),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
