<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->components([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->required(),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->required()
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
