<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
{
    protected $fillable = [
        'bank_name',
        'transaction_date',
        'description',
        'amount',
        'type',
        'reference',
        'matched_invoice_id',
        'matched_journal_entry_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function matchedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'matched_invoice_id');
    }

    public function matchedJournalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'matched_journal_entry_id');
    }
}