<?php

namespace App\Filament\Resources\ChartOfAccounts;

use App\Filament\Resources\ChartOfAccounts\Pages\CreateChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\EditChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\ListChartOfAccounts;
use App\Filament\Resources\ChartOfAccounts\Schemas\ChartOfAccountForm;
use App\Filament\Resources\ChartOfAccounts\Tables\ChartOfAccountsTable;
use App\Models\ChartOfAccount;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Chart of Accounts';

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
        return ChartOfAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChartOfAccountsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChartOfAccounts::route('/'),
            'create' => CreateChartOfAccount::route('/create'),
            'edit' => EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
