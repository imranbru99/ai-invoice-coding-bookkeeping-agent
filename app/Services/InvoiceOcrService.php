<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Str;

class InvoiceOcrService
{
    public function __construct(private readonly ModuleManager $modules)
    {
    }

    public function ingest(array $data, ?User $actor = null): Invoice
    {
        if (! $this->modules->enabled('invoice_ocr')) {
            abort(403, 'Invoice OCR module is disabled.');
        }

        $vendor = Vendor::query()->firstOrCreate(
            ['email' => $data['vendor_email'] ?? null],
            [
                'name' => $data['vendor_name'] ?? 'Unknown Vendor',
                'slug' => Str::slug($data['vendor_name'] ?? 'vendor').'-'.Str::lower(Str::random(4)),
                'phone' => $data['vendor_phone'] ?? null,
                'status' => 'active',
            ]
        );

        $lines = $data['lines'] ?? $this->mockOcrLines($data);
        $subtotal = collect($lines)->sum('amount');
        $tax = (float) ($data['tax'] ?? round($subtotal * 0.08, 2));

        $invoice = Invoice::query()->create([
            'invoice_number' => $data['invoice_number'] ?? $this->nextInvoiceNumber(),
            'vendor_id' => $vendor->id,
            'uploaded_by' => $actor?->id,
            'status' => 'ocr_complete',
            'invoice_date' => $data['invoice_date'] ?? now()->toDateString(),
            'due_date' => $data['due_date'] ?? now()->addDays(30)->toDateString(),
            'currency' => $data['currency'] ?? 'USD',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'ocr_text' => $data['ocr_text'] ?? 'OCR extracted invoice text placeholder.',
            'ocr_payload' => $data['ocr_payload'] ?? ['source' => 'demo', 'lines' => $lines],
        ]);

        foreach ($lines as $index => $line) {
            InvoiceLine::query()->create([
                'invoice_id' => $invoice->id,
                'line_number' => $index + 1,
                'description' => $line['description'],
                'quantity' => $line['quantity'] ?? 1,
                'unit_price' => $line['unit_price'] ?? $line['amount'],
                'amount' => $line['amount'],
                'expense_category' => $line['expense_category'] ?? null,
            ]);
        }

        return $invoice->load('lines', 'vendor');
    }

    public function nextInvoiceNumber(): string
    {
        $seq = Invoice::query()->count() + 1;

        return sprintf('INV-%s-%04d', now()->format('Y'), $seq);
    }

    private function mockOcrLines(array $data): array
    {
        return $data['mock_lines'] ?? [
            ['description' => 'SaaS subscription', 'amount' => 299.00, 'expense_category' => 'software'],
            ['description' => 'Implementation services', 'amount' => 1200.00, 'expense_category' => 'professional_services'],
        ];
    }
}