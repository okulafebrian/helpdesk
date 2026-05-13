<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Carbon\Carbon;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->inlineLabel()
                            ->schema([
                                Select::make('status')
                                    ->options(TicketStatus::class)
                                    ->live()
                                    ->required(),
                                Select::make('priority')
                                    ->options(TicketPriority::class)
                                    ->required(),
                                Select::make('category')
                                    ->options(TicketCategory::class)
                                    ->required(),
                                Select::make('assignee_id')
                                    ->relationship('assignee', 'name'),
                                Select::make('requester_id')
                                    ->relationship('requester', 'name')
                                    ->required(),
                                Select::make('location_id')
                                    ->relationship('location', 'name')
                                    ->required(),
                            ]), 
                    ]),
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof CreateRecord),

                Group::make()
                    ->schema([
                        Section::make('Description')
                            ->schema([
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->markdown(),
                            ])
                            ->columnSpan(2),
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
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->hidden(fn ($livewire) => $livewire instanceof CreateRecord),
            ])
            ->columns(1);
    }
}
