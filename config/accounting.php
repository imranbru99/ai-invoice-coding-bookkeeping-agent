<?php

return [
    'app_name' => env('ACCOUNTING_APP_NAME', 'AI Invoice Coding & Bookkeeping Agent'),
    'product_name' => env('ACCOUNTING_APP_NAME', 'AI Invoice Coding & Bookkeeping Agent'),
    'brand' => env('ACCOUNTING_BRAND', 'LedgerCode AI'),
    'accent' => '#059669',
    'invoice_statuses' => [
        'draft' => 'Draft',
        'ocr_complete' => 'OCR Complete',
        'coding' => 'Coding',
        'coded' => 'Coded',
        'posted' => 'Posted',
        'reconciled' => 'Reconciled',
        'rejected' => 'Rejected',
    ],
    'journal_statuses' => [
        'draft' => 'Draft',
        'pending' => 'Pending Review',
        'posted' => 'Posted',
        'reversed' => 'Reversed',
    ],
    'account_types' => [
        'asset' => 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity',
        'revenue' => 'Revenue',
        'expense' => 'Expense',
    ],
    'close_categories' => [
        'reconciliation' => 'Reconciliation',
        'accruals' => 'Accruals',
        'review' => 'Review',
        'reporting' => 'Reporting',
        'sign_off' => 'Sign-off',
    ],
];