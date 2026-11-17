<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\ExpenseCodeSuggestion;
use App\Models\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExpenseCodingService
{
    public function __construct(private readonly ModuleManager $modules)
    {
    }

    public function suggestForInvoice(Invoice $invoice): Collection
    {
        if (! $this->modules->enabled('expense_coding')) {
            abort(403, 'Expense Coding module is disabled.');
        }

        $invoice->loadMissing('lines');
        $suggestions = collect();

        foreach ($invoice->lines as $line) {
            $account = $this->resolveGlAccount($line->expense_category, $line->description);
            $confidence = $this->confidenceScore($line->description, $account?->code);

            $suggestion = ExpenseCodeSuggestion::query()->updateOrCreate(
                [
                    'invoice_id' => $invoice->id,
                    'invoice_line_id' => $line->id,
                ],
                [
                    'gl_account_id' => $account?->id,
                    'confidence' => $confidence,
                    'rationale' => $this->rationale($line->description, $account),
                    'status' => $confidence >= 80 ? 'accepted' : 'pending',
                    'suggested_by' => 'ledgercode-ai',
                ]
            );

            $line->update([
                'suggested_gl_code' => $account?->code,
                'gl_account_id' => $account?->id,
                'confidence' => $confidence,
            ]);

            $suggestions->push($suggestion);
        }

        $invoice->update(['status' => 'coded']);

        return $suggestions;
    }

    public function resolveGlAccount(?string $category, string $description): ?ChartOfAccount
    {
        $map = [
            'software' => '6100',
            'professional_services' => '6200',
            'office' => '6300',
            'travel' => '6400',
            'utilities' => '6500',
        ];

        $code = $map[$category] ?? null;

        if (! $code) {
            $needle = Str::lower($description);
            $code = match (true) {
                Str::contains($needle, ['saas', 'software', 'license']) => '6100',
                Str::contains($needle, ['consult', 'legal', 'service']) => '6200',
                default => '6999',
            };
        }

        return ChartOfAccount::query()->where('code', $code)->first()
            ?? ChartOfAccount::query()->where('type', 'expense')->first();
    }

    private function confidenceScore(string $description, ?string $code): float
    {
        if (! $code) {
            return 42.0;
        }

        return Str::length($description) > 12 ? 88.5 : 72.0;
    }

    private function rationale(string $description, ?ChartOfAccount $account): string
    {
        if (! $account) {
            return 'Low confidence — manual GL review recommended for: '.$description;
        }

        return sprintf('Mapped "%s" to %s (%s) based on category keywords.', $description, $account->code, $account->name);
    }
}