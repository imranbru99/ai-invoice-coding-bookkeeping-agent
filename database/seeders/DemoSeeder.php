<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\BankTransaction;
use App\Models\ChartOfAccount;
use App\Models\CloseChecklistItem;
use App\Models\ModuleSetting;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ExpenseCodingService;
use App\Services\GlPostingService;
use App\Services\InvoiceOcrService;
use App\Services\VendorReconciliationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('modules') as $key => $module) {
            ModuleSetting::query()->updateOrCreate(
                ['key' => $key],
                ['enabled' => (bool) ($module['enabled'] ?? true), 'meta' => ['label' => $module['label'] ?? $key]]
            );
        }

        $super = User::query()->updateOrCreate(
            ['email' => 'superadmin@ledgercode.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::SuperAdmin,
                'title' => 'Controller',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@ledgercode.test'],
            [
                'name' => 'Finance Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'title' => 'Finance Administrator',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'manager@ledgercode.test'],
            [
                'name' => 'AP Manager',
                'password' => Hash::make('password'),
                'role' => UserRole::Manager,
                'title' => 'Accounts Payable Manager',
                'is_active' => true,
            ]
        );

        $staff = User::query()->updateOrCreate(
            ['email' => 'staff@ledgercode.test'],
            [
                'name' => 'AP Specialist',
                'password' => Hash::make('password'),
                'role' => UserRole::Staff,
                'title' => 'AP Specialist',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'demo@ledgercode.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => UserRole::Demo,
                'title' => 'Demo Accountant',
                'is_demo' => true,
                'is_active' => true,
            ]
        );

        $clientUser = User::query()->updateOrCreate(
            ['email' => 'client@ledgercode.test'],
            [
                'name' => 'Vendor Contact',
                'password' => Hash::make('password'),
                'role' => UserRole::Client,
                'title' => 'Vendor Portal',
                'is_active' => true,
            ]
        );

        $accounts = [
            ['code' => '1000', 'name' => 'Operating Cash', 'type' => 'asset'],
            ['code' => '1350', 'name' => 'Input Tax Receivable', 'type' => 'asset'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability'],
            ['code' => '6100', 'name' => 'Software & Subscriptions', 'type' => 'expense'],
            ['code' => '6200', 'name' => 'Professional Services', 'type' => 'expense'],
            ['code' => '6300', 'name' => 'Office Supplies', 'type' => 'expense'],
            ['code' => '6400', 'name' => 'Travel & Entertainment', 'type' => 'expense'],
            ['code' => '6500', 'name' => 'Utilities', 'type' => 'expense'],
            ['code' => '6999', 'name' => 'Miscellaneous Expense', 'type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::query()->updateOrCreate(
                ['code' => $account['code']],
                [
                    'name' => $account['name'],
                    'type' => $account['type'],
                    'is_active' => true,
                    'description' => $account['name'],
                ]
            );
        }

        $vendor = Vendor::query()->updateOrCreate(
            ['email' => 'billing@northcloud.test'],
            [
                'name' => 'NorthCloud SaaS',
                'slug' => 'northcloud-saas',
                'phone' => '+1-555-0100',
                'tax_id' => 'TAX-77821',
                'payment_terms' => 'Net 30',
                'address' => '100 Market St, San Francisco, CA',
                'aliases' => ['North Cloud', 'NCloud'],
                'portal_user_id' => $clientUser->id,
                'status' => 'active',
                'balance' => 1619.00,
            ]
        );

        Vendor::query()->updateOrCreate(
            ['email' => 'ap@brightoffice.test'],
            [
                'name' => 'Bright Office Supply',
                'slug' => 'bright-office-supply',
                'payment_terms' => 'Net 15',
                'status' => 'active',
                'balance' => 420.50,
                'aliases' => ['BrightOffice'],
            ]
        );

        $ocr = app(InvoiceOcrService::class);
        $coding = app(ExpenseCodingService::class);
        $posting = app(GlPostingService::class);
        $recon = app(VendorReconciliationService::class);

        $invoice = $ocr->ingest([
            'vendor_email' => $vendor->email,
            'vendor_name' => $vendor->name,
            'invoice_number' => 'INV-2026-0001',
            'invoice_date' => now()->subDays(5)->toDateString(),
            'due_date' => now()->addDays(25)->toDateString(),
            'tax' => 119.92,
            'lines' => [
                ['description' => 'SaaS subscription Pro', 'amount' => 299.00, 'expense_category' => 'software'],
                ['description' => 'Implementation consulting', 'amount' => 1200.00, 'expense_category' => 'professional_services'],
            ],
        ], $staff);

        $coding->suggestForInvoice($invoice);
        $posting->postInvoice($invoice->fresh('lines'), $super);

        $recon->reconcile(
            $vendor,
            now()->startOfMonth(),
            now()->endOfMonth(),
            1619.00
        );

        BankTransaction::query()->updateOrCreate(
            ['reference' => 'ACH-LC-9001'],
            [
                'bank_name' => 'First Emerald Bank',
                'transaction_date' => now()->subDays(2)->toDateString(),
                'description' => 'PAYMENT NORTHCLOUD SAAS',
                'amount' => -1619.00,
                'type' => 'debit',
                'matched_invoice_id' => $invoice->id,
                'status' => 'matched',
            ]
        );

        BankTransaction::query()->updateOrCreate(
            ['reference' => 'ACH-LC-9002'],
            [
                'bank_name' => 'First Emerald Bank',
                'transaction_date' => now()->subDay()->toDateString(),
                'description' => 'UNKNOWN VENDOR DRAFT',
                'amount' => -87.40,
                'type' => 'debit',
                'status' => 'unmatched',
            ]
        );

        $period = now()->format('Y-m');
        $checklist = [
            ['title' => 'Lock AP invoice cut-off', 'category' => 'reconciliation', 'sort_order' => 1],
            ['title' => 'Complete vendor statement reconciliations', 'category' => 'reconciliation', 'sort_order' => 2],
            ['title' => 'Post accrued expenses', 'category' => 'accruals', 'sort_order' => 3],
            ['title' => 'Review unposted AI coding suggestions', 'category' => 'review', 'sort_order' => 4],
            ['title' => 'Generate P&L draft pack', 'category' => 'reporting', 'sort_order' => 5],
            ['title' => 'Controller sign-off', 'category' => 'sign_off', 'sort_order' => 6],
        ];

        foreach ($checklist as $item) {
            CloseChecklistItem::query()->updateOrCreate(
                ['period' => $period, 'title' => $item['title']],
                [
                    'category' => $item['category'],
                    'sort_order' => $item['sort_order'],
                    'is_complete' => $item['sort_order'] <= 2,
                    'completed_by' => $item['sort_order'] <= 2 ? $super->id : null,
                    'completed_at' => $item['sort_order'] <= 2 ? now() : null,
                ]
            );
        }
    }
}
