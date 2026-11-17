<?php

namespace App\Filament\Resources\ExpenseCodeSuggestions;

use App\Filament\Resources\ExpenseCodeSuggestions\Pages\CreateExpenseCodeSuggestion;
use App\Filament\Resources\ExpenseCodeSuggestions\Pages\EditExpenseCodeSuggestion;
use App\Filament\Resources\ExpenseCodeSuggestions\Pages\ListExpenseCodeSuggestions;
use App\Filament\Resources\ExpenseCodeSuggestions\Schemas\ExpenseCodeSuggestionForm;
use App\Filament\Resources\ExpenseCodeSuggestions\Tables\ExpenseCodeSuggestionsTable;
use App\Models\ExpenseCodeSuggestion;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ExpenseCodeSuggestionResource extends Resource
{
    protected static ?string $model = ExpenseCodeSuggestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Expense Coding';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'GL Suggestions';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('expense_coding');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return ExpenseCodeSuggestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpenseCodeSuggestionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenseCodeSuggestions::route('/'),
            'create' => CreateExpenseCodeSuggestion::route('/create'),
            'edit' => EditExpenseCodeSuggestion::route('/{record}/edit'),
        ];
    }
}
