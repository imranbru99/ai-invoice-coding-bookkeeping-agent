<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('close_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7);
            $table->string('title');
            $table->string('category')->default('review');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('close_checklist_items');
    }
};