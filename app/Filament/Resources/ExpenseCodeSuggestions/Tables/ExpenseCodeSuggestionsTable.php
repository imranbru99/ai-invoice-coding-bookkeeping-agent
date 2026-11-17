<?php

namespace App\Filament\Resources\ExpenseCodeSuggestions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpenseCodeSuggestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')->label('Invoice'),
                TextColumn::make('chartOfAccount.code')->label('GL'),
                TextColumn::make('confidence')->suffix('%'),
                TextColumn::make('status')->badge(),
            ])
            ->filters([])
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
