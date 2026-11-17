<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CloseChecklistItem extends Model
{
    protected $fillable = [
        'period',
        'title',
        'category',
        'sort_order',
        'is_complete',
        'completed_by',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_complete' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}