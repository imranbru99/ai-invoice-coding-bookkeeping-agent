<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'vendor_id',
        'uploaded_by',
        'status',
        'invoice_date',
        'due_date',
        'currency',
        'subtotal',
        'tax',
        'total',
        'ocr_text',
        'ocr_payload',
        'gl_posted_at',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'ocr_payload' => 'array',
            'gl_posted_at' => 'datetime',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function expenseSuggestions(): HasMany
    {
        return $this->hasMany(ExpenseCodeSuggestion::class);
    }

    public function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'matched_invoice_id');
    }
}