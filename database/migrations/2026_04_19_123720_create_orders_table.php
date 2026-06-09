<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke user yang memesan
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Nomor unik pesanan, misalnya: INV-20260606-0001
            $table->string('invoice_number')->unique();

            // Alamat pengiriman barang
            $table->text('shipping_address')->nullable();

            $table->foreignId('courier_id')->nullable()->constrained('couriers')->onDelete('set null');
            // Total tagihan keseluruhan (menggunakan decimal agar presisi untuk mata uang)
            $table->decimal('grand_total', 12, 2);

            // STATUS 1: Status Pengiriman Barang
            // Defaultnya 'pending' (menunggu diproses)
            $table->enum('delivery_status', [
                'pending',
                'processing',
                'shipping',
                'delivered',
                'cancelled'

            ])->default('pending')->nullable();

            // STATUS 2: Status Pembayaran (Untuk sistem cicil/lunas)
            // Defaultnya 'belum_lunas'
            $table->enum('payment_status', [
                'belum_lunas',
                'lunas'
            ])->default('belum_lunas');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
