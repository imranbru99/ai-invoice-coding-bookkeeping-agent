<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceLine extends Model
{
    protected $fillable = [
        'invoice_id',
        'line_number',
        'description',
        'quantity',
        'unit_price',
        'amount',
        'expense_category',
        'suggested_gl_code',
        'gl_account_id',
        'confidence',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'amount' => 'decimal:2',
            'confidence' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function glAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'gl_account_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(ExpenseCodeSuggestion::class);
    }
}