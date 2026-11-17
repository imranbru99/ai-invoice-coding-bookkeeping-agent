<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentInvoicesWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Invoices';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Invoice::query()->with('vendor')->latest()->limit(8))
            ->columns([
                TextColumn::make('invoice_number')->label('Invoice #')->searchable(),
                TextColumn::make('vendor.name')->label('Vendor'),
                TextColumn::make('total')->money('USD'),
                TextColumn::make('status')->badge(),
                TextColumn::make('invoice_date')->date(),
            ])
            ->paginated(false);
    }
}
