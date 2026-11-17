<?php

namespace App\Filament\Resources\ExpenseCodeSuggestions\Pages;

use App\Filament\Resources\ExpenseCodeSuggestions\ExpenseCodeSuggestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenseCodeSuggestions extends ListRecords
{
    protected static string $resource = ExpenseCodeSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
