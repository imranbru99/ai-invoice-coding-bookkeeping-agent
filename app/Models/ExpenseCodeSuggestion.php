<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseCodeSuggestion extends Model
{
    protected $fillable = [
        'invoice_id',
        'invoice_line_id',
        'gl_account_id',
        'confidence',
        'rationale',
        'status',
        'suggested_by',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceLine(): BelongsTo
    {
        return $this->belongsTo(InvoiceLine::class);
    }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'gl_account_id');
    }
}