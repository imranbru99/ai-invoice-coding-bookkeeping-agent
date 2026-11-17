<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('address')->nullable();
            $table->json('aliases')->nullable();
            $table->foreignId('portal_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('active');
            $table->decimal('balance', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};