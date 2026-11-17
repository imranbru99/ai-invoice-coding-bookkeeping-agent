<?php

namespace App\Filament\Resources\VendorReconciliations;

use App\Filament\Resources\VendorReconciliations\Pages\CreateVendorReconciliation;
use App\Filament\Resources\VendorReconciliations\Pages\EditVendorReconciliation;
use App\Filament\Resources\VendorReconciliations\Pages\ListVendorReconciliations;
use App\Filament\Resources\VendorReconciliations\Schemas\VendorReconciliationForm;
use App\Filament\Resources\VendorReconciliations\Tables\VendorReconciliationsTable;
use App\Models\VendorReconciliation;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorReconciliationResource extends Resource
{
    protected static ?string $model = VendorReconciliation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'Vendors & Reconciliation';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Vendor Reconciliations';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('vendor_reconciliation');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return VendorReconciliationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorReconciliationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorReconciliations::route('/'),
            'create' => CreateVendorReconciliation::route('/create'),
            'edit' => EditVendorReconciliation::route('/{record}/edit'),
        ];
    }
}
