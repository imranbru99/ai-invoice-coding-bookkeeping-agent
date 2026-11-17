<?php

namespace App\Services;

use App\Models\CloseChecklistItem;
use App\Models\User;
use Illuminate\Support\Carbon;

class CloseChecklistService
{
    public function __construct(private readonly ModuleManager $modules)
    {
    }

    public function seedPeriod(string $period): void
    {
        if (! $this->modules->enabled('close_checklist')) {
            abort(403, 'Close Checklist module is disabled.');
        }

        $templates = [
            ['Reconcile bank accounts', 'reconciliation', 1],
            ['Review uncoded invoices', 'review', 2],
            ['Post accrual entries', 'accruals', 3],
            ['Vendor statement reconciliation', 'reconciliation', 4],
            ['Management review sign-off', 'sign_off', 5],
        ];

        foreach ($templates as [$title, $category, $sort]) {
            CloseChecklistItem::query()->firstOrCreate(
                ['period' => $period, 'title' => $title],
                [
                    'category' => $category,
                    'sort_order' => $sort,
                    'is_complete' => false,
                ]
            );
        }
    }

    public function completeItem(CloseChecklistItem $item, User $user): CloseChecklistItem
    {
        $item->update([
            'is_complete' => true,
            'completed_by' => $user->id,
            'completed_at' => Carbon::now(),
        ]);

        return $item;
    }
}