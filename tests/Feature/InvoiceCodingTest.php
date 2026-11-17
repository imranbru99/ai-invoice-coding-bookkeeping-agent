<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\ChartOfAccount;
use App\Models\User;
use App\Services\ExpenseCodingService;
use App\Services\InvoiceOcrService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceCodingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ocr_and_coding_pipeline(): void
    {
        ChartOfAccount::query()->create([
            'code' => '6100',
            'name' => 'Software & Subscriptions',
            'type' => 'expense',
            'is_active' => true,
        ]);
        ChartOfAccount::query()->create([
            'code' => '6200',
            'name' => 'Professional Services',
            'type' => 'expense',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => UserRole::Staff]);

        $invoice = app(InvoiceOcrService::class)->ingest([
            'vendor_name' => 'Test Vendor LLC',
            'vendor_email' => 'vendor@example.test',
            'lines' => [
                ['description' => 'SaaS license', 'amount' => 99.00, 'expense_category' => 'software'],
            ],
        ], $user);

        $suggestions = app(ExpenseCodingService::class)->suggestForInvoice($invoice);

        $this->assertDatabaseHas('invoices', ['invoice_number' => $invoice->invoice_number]);
        $this->assertGreaterThan(0, $suggestions->count());
        $this->assertEquals('coded', $invoice->fresh()->status);
    }
}
