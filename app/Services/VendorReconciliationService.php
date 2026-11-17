<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\VendorReconciliation;
use Illuminate\Support\Carbon;

class VendorReconciliationService
{
    public function __construct(private readonly ModuleManager $modules)
    {
    }

    public function reconcile(Vendor $vendor, Carbon $periodStart, Carbon $periodEnd, float $statementBalance): VendorReconciliation
    {
        if (! $this->modules->enabled('vendor_reconciliation')) {
            abort(403, 'Vendor Reconciliation module is disabled.');
        }

        $ledgerBalance = (float) Invoice::query()
            ->where('vendor_id', $vendor->id)
            ->whereBetween('invoice_date', [$periodStart, $periodEnd])
            ->whereIn('status', ['posted', 'reconciled', 'coded'])
            ->sum('total');

        $variance = round($statementBalance - $ledgerBalance, 2);
        $status = abs($variance) < 0.01 ? 'balanced' : 'variance';

        return VendorReconciliation::query()->updateOrCreate(
            [
                'vendor_id' => $vendor->id,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
            ],
            [
                'statement_balance' => $statementBalance,
                'ledger_balance' => $ledgerBalance,
                'variance' => $variance,
                'status' => $status,
                'notes' => $status === 'balanced'
                    ? 'Statement matches ledger for the period.'
                    : 'Review open invoices and statement timing differences.',
            ]
        );
    }
}