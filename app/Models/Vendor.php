<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'tax_id',
        'payment_terms',
        'address',
        'aliases',
        'portal_user_id',
        'status',
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'aliases' => 'array',
            'balance' => 'decimal:2',
        ];
    }

    public function portalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'portal_user_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(VendorReconciliation::class);
    }
}