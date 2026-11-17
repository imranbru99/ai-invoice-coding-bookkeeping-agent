<?php

return [
    'invoice_ocr' => [
        'enabled' => env('MODULE_INVOICE_OCR', true),
        'label' => 'Invoice OCR',
        'description' => 'Scan, ingest, and extract invoice header and line detail from PDFs and scans.',
    ],
    'expense_coding' => [
        'enabled' => env('MODULE_EXPENSE_CODING', true),
        'label' => 'Expense Coding',
        'description' => 'AI expense classification and GL code suggestions with confidence scores.',
    ],
    'gl_posting' => [
        'enabled' => env('MODULE_GL_POSTING', true),
        'label' => 'GL Posting',
        'description' => 'Chart of accounts, journal entries, and automated posting from coded invoices.',
    ],
    'vendor_reconciliation' => [
        'enabled' => env('MODULE_VENDOR_RECONCILIATION', true),
        'label' => 'Vendor Reconciliation',
        'description' => 'Vendor master records, statement balances, and variance reconciliation.',
    ],
    'bank_feed' => [
        'enabled' => env('MODULE_BANK_FEED', true),
        'label' => 'Bank Feed',
        'description' => 'Import bank transactions and match them to invoices and journal entries.',
    ],
    'close_checklist' => [
        'enabled' => env('MODULE_CLOSE_CHECKLIST', true),
        'label' => 'Close Checklist',
        'description' => 'Month-end close tasks, sign-offs, and period completion tracking.',
    ],
    'attendance_ops' => [
        'enabled' => env('MODULE_ATTENDANCE_OPS', false),
        'label' => 'Staff Attendance',
        'description' => 'Optional attendance hub integration for accounting operations teams.',
    ],
];