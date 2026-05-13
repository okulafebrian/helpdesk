<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use App\Enums\SlaStatus;
use App\Enums\TicketStatus;
use App\Models\Comment;
use App\Models\Ticket;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('body')
                    ->required()
                    ->maxLength(1000),
                Toggle::make('is_internal')
                    ->label('Internal Note'),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('author.name')
                            ->weight(FontWeight::SemiBold)
                            ->grow(false),
                        TextColumn::make('created_at')
                            ->since()
                            ->size(TextSize::ExtraSmall)
                            ->color('gray')
                            ->alignEnd(),
                    ]),
                    TextColumn::make('body')
                        ->markdown(),
                ])
                ->space(1.5),
            ])
            ->recordClasses(fn (Comment $record) => $record->is_internal ? 'bg-amber-100' : null)
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->mutateDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
                    ->after(function (Comment $record) {                        
                        if (!$record->ticket->first_response_at && !$record->is_internal) {
                            $responseSlaStatus = now() > $record->ticket->response_due_at 
                                ? SlaStatus::BREACHED 
                                : SlaStatus::MET;
                            
                            $record->ticket->update([
                                'first_response_at' => now(),
                                'response_sla_status' => $responseSlaStatus,
                                'status' => TicketStatus::OPEN,
                            ]);

                            $this->dispatch('comment-created');
                        }   
                    }),
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
