<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_code_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_line_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gl_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->decimal('confidence', 5, 2)->default(0);
            $table->text('rationale')->nullable();
            $table->string('status')->default('pending');
            $table->string('suggested_by')->default('ledgercode-ai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_code_suggestions');
    }
};