<?php

namespace App\Filament\Resources\Tickets;

use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use BackedEnum;
use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Schema $schema): Schema
    {
        return TicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('agent')) {
            return parent::getEloquentQuery()->where('assignee_id', auth()->id());
        }

        return parent::getEloquentQuery();
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Description')
                            ->schema([
                                TextEntry::make('description')
                                    ->markdown()
                                    ->hiddenLabel(),
                            ]),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('SLA')
                            ->schema([
                                TextEntry::make('response_due_at')
                                    ->label('Response Due Time')
                                    ->badge()
                                    ->formatStateUsing(function ($state) {                                        
                                        if (blank($state)) return '';

                                        $due = Carbon::parse($state);
                                        $diff = now()->diffForHumans($due, true);

                                        return now()->gt($due) ? "-{$diff}" : "{$diff}";
                                    })
                                    ->color(fn ($state) => now()->gt(Carbon::parse($state)) ? 'danger' : 'success')
                                    ->visible(fn ($record) => !filled($record->first_response_at)),
                                
                                TextEntry::make('first_response_at')
                                    ->label('First Response Time')
                                    ->dateTime('M j, Y H:i')
                                    ->visible(fn ($record) => filled($record->first_response_at)),
                                
                                TextEntry::make('resolution_due_at')
                                    ->label('Resolution Due Time')
                                    ->badge()
                                    ->formatStateUsing(function ($state) {                                        
                                        if (blank($state)) return '';

                                        $due = Carbon::parse($state);
                                        $diff = now()->diffForHumans($due, true);

                                        return now()->gt($due) ? "-{$diff}" : "{$diff}";
                                    })
                                    ->color(fn ($state) => now()->gt(Carbon::parse($state)) ? 'danger' : 'success')
                                    ->visible(fn ($record) => !filled($record->resolved_at)),
                                
                                TextEntry::make('resolved_at')
                                    ->label('Resolution Time')
                                    ->dateTime('M j, Y H:i')
                                    ->visible(fn ($record) => filled($record->resolved_at)),
                            ]),
                        Section::make('Ticket Details')
                            ->inlineLabel()
                            ->schema([
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('priority')
                                    ->badge(),
                                TextEntry::make('assignee.name')
                                    ->label('Assignee'),
                                TextEntry::make('requester.name')
                                    ->label('Requester'),
                                TextEntry::make('location.name')
                                    ->label('Location'),
                                TextEntry::make('category'),
                            ]),
                    ])
                    ->columnSpan(1)
            ])
            ->columns(3);
    }
}
