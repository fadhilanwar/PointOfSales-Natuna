<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel orders. Jika order dihapus, riwayat bayarnya ikut terhapus.
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Nominal uang yang dibayarkan pada transaksi ini
            $table->decimal('amount', 12, 2);

            // Tipe bayarnya: bayar lunas sekaligus atau bayar sebagian (cicil)
            $table->enum('type', ['full_payment', 'installment']);

            // Metode pembayarannya
            $table->enum('payment_method', ['transfer', 'cod']);

            // Tempat menyimpan nama file foto bukti transfer (bisa kosong kalau COD)
            $table->string('payment_proof_path')->nullable();

            // Status verifikasi pembayaran oleh Admin. Default 'pending'
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
