<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['pending', 'received', 'cancelled'])->default('pending');
        $table->decimal('grand_total', 12, 2)->default(0);
        $table->text('notes')->nullable(); // Catatan tambahan opsional
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
