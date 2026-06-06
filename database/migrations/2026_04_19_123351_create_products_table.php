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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique()->nullable(); // Untuk scan POS
            
            // Relasi kategori dari master
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            
            // Kolom nama dan tambahan dari haidar (slug, deskripsi)
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            $table->decimal('cost_price', 12, 2); // Harga modal
            $table->decimal('base_price', 12, 2); // Harga jual
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5); // Alert batas minimum
            
            // Image path cukup ditulis satu kali saja
            $table->string('image_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};