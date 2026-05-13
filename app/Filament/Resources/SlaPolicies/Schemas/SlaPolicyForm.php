<?php

namespace App\Filament\Resources\SlaPolicies\Schemas;

use App\Enums\TicketPriority;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SlaPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('priority')
                    ->options(TicketPriority::class)
                    ->required(),
                Grid::make(2)
                    ->schema([
                        TextInput::make('response_hrs')
                            ->label('Response Time')
                            ->suffix('hrs')
                            ->numeric()
                            ->required(),
                        TextInput::make('resolution_hrs')
                            ->label('Resolution Time')
                            ->suffix('hrs')
                            ->numeric()
                            ->required(),
                    ])
            ]);
    }
}
