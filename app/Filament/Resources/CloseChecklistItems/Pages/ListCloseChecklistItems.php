<?php

namespace App\Filament\Resources\CloseChecklistItems\Pages;

use App\Filament\Resources\CloseChecklistItems\CloseChecklistItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCloseChecklistItems extends ListRecords
{
    protected static string $resource = CloseChecklistItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
