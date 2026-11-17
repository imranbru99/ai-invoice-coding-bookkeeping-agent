<?php

namespace App\Filament\Resources\JournalEntries\Pages;

use App\Filament\Resources\JournalEntries\JournalEntryResource;
use Filament\Resources\Pages\EditRecord;

class EditJournalEntry extends EditRecord
{
    protected static string $resource = JournalEntryResource::class;
}
