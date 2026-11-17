<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('line_number')->default(1);
            $table->string('description');
            $table->decimal('quantity', 12, 3)->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('expense_category')->nullable();
            $table->string('suggested_gl_code')->nullable();
            $table->foreignId('gl_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
    }
};