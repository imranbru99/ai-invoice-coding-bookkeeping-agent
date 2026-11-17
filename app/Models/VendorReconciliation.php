<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorReconciliation extends Model
{
    protected $fillable = [
        'vendor_id',
        'period_start',
        'period_end',
        'statement_balance',
        'ledger_balance',
        'variance',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'statement_balance' => 'decimal:2',
            'ledger_balance' => 'decimal:2',
            'variance' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}