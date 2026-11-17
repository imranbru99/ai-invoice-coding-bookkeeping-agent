<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->date('transaction_date');
            $table->string('description');
            $table->decimal('amount', 14, 2);
            $table->string('type')->default('debit');
            $table->string('reference')->nullable();
            $table->foreignId('matched_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('matched_journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->string('status')->default('unmatched');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};