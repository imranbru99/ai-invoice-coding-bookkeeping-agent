<?php

namespace App\Filament\Resources\JournalEntries;

use App\Filament\Resources\JournalEntries\Pages\CreateJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\EditJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\ListJournalEntries;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryForm;
use App\Filament\Resources\JournalEntries\Tables\JournalEntriesTable;
use App\Models\JournalEntry;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Journal Entries';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('gl_posting');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return JournalEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalEntriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJournalEntries::route('/'),
            'create' => CreateJournalEntry::route('/create'),
            'edit' => EditJournalEntry::route('/{record}/edit'),
        ];
    }
}
