<?php

namespace App\Filament\Widgets;

use App\Models\CloseChecklistItem;
use App\Models\Invoice;
use App\Models\VendorReconciliation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountingStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $coded = Invoice::query()->whereIn('status', ['coded', 'posted', 'ocr_complete'])->count();
        $posted = Invoice::query()->whereNotNull('gl_posted_at')->count();
        $reconOpen = VendorReconciliation::query()->whereIn('status', ['open', 'variance'])->count();
        $closeDone = CloseChecklistItem::query()->where('is_complete', true)->count();
        $closeTotal = max(CloseChecklistItem::query()->count(), 1);

        return [
            Stat::make('Invoices Coded', $coded)
                ->description('OCR + coding pipeline')
                ->color('primary'),
            Stat::make('GL Auto-Posted', $posted)
                ->description('Invoices with journal post')
                ->color('success'),
            Stat::make('Vendor Recons Open', $reconOpen)
                ->description('Awaiting match / variance')
                ->color('warning'),
            Stat::make('Close Progress', round(($closeDone / $closeTotal) * 100).'%')
                ->description("{$closeDone} of {$closeTotal} checklist items")
                ->color('info'),
        ];
    }
}
