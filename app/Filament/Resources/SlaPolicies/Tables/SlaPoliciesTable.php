<?php

namespace App\Filament\Resources\SlaPolicies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SlaPoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priority')
                    ->badge(),
                TextColumn::make('response_hrs')
                    ->label('Response Time')
                    ->formatStateUsing(fn ($state) => $state . ' ' . Str::plural('hour', $state)),
                TextColumn::make('resolution_hrs')
                    ->label('Resolution Time')
                    ->formatStateUsing(fn ($state) => $state . ' ' . Str::plural('hour', $state)),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
