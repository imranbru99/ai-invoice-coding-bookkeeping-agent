<?php

namespace App\Filament\Resources\CloseChecklistItems;

use App\Filament\Resources\CloseChecklistItems\Pages\CreateCloseChecklistItem;
use App\Filament\Resources\CloseChecklistItems\Pages\EditCloseChecklistItem;
use App\Filament\Resources\CloseChecklistItems\Pages\ListCloseChecklistItems;
use App\Filament\Resources\CloseChecklistItems\Schemas\CloseChecklistItemForm;
use App\Filament\Resources\CloseChecklistItems\Tables\CloseChecklistItemsTable;
use App\Models\CloseChecklistItem;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CloseChecklistItemResource extends Resource
{
    protected static ?string $model = CloseChecklistItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Month-End Close';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Close Checklist';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('close_checklist');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return CloseChecklistItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CloseChecklistItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCloseChecklistItems::route('/'),
            'create' => CreateCloseChecklistItem::route('/create'),
            'edit' => EditCloseChecklistItem::route('/{record}/edit'),
        ];
    }
}
