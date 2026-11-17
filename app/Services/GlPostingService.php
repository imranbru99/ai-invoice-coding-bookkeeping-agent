<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\User;

class GlPostingService
{
    public function __construct(private readonly ModuleManager $modules)
    {
    }

    public function postInvoice(Invoice $invoice, ?User $actor = null): JournalEntry
    {
        if (! $this->modules->enabled('gl_posting')) {
            abort(403, 'GL Posting module is disabled.');
        }

        $invoice->loadMissing('lines.glAccount', 'vendor');
        $expenseTotal = $invoice->lines->sum('amount');
        $apAccount = ChartOfAccount::query()->where('code', '2000')->first();

        $entry = JournalEntry::query()->create([
            'entry_number' => $this->nextEntryNumber(),
            'entry_date' => $invoice->invoice_date,
            'description' => 'AP invoice post: '.$invoice->invoice_number,
            'status' => 'posted',
            'posted_by' => $actor?->id,
            'posted_at' => now(),
            'source_type' => Invoice::class,
            'source_id' => $invoice->id,
        ]);

        foreach ($invoice->lines as $line) {
            JournalLine::query()->create([
                'journal_entry_id' => $entry->id,
                'gl_account_id' => $line->gl_account_id,
                'debit' => $line->amount,
                'credit' => 0,
                'memo' => $line->description,
            ]);
        }

        if ($invoice->tax > 0) {
            $taxAccount = ChartOfAccount::query()->where('code', '1350')->first();
            if ($taxAccount) {
                JournalLine::query()->create([
                    'journal_entry_id' => $entry->id,
                    'gl_account_id' => $taxAccount->id,
                    'debit' => $invoice->tax,
                    'credit' => 0,
                    'memo' => 'Input tax',
                ]);
            }
        }

        if ($apAccount) {
            JournalLine::query()->create([
                'journal_entry_id' => $entry->id,
                'gl_account_id' => $apAccount->id,
                'debit' => 0,
                'credit' => $expenseTotal + $invoice->tax,
                'memo' => 'Vendor AP: '.$invoice->vendor?->name,
            ]);
        }

        $invoice->update([
            'status' => 'posted',
            'gl_posted_at' => now(),
        ]);

        return $entry->load('lines.glAccount');
    }

    public function nextEntryNumber(): string
    {
        $seq = JournalEntry::query()->count() + 1;

        return sprintf('JE-%s-%04d', now()->format('Y'), $seq);
    }
}