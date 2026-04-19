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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('shipping_address')->nullable();

            // Tipe Transaksi
            $table->enum('order_source', ['pos', 'online']);

            // State Machine Status
            $table->enum('status', [
                'pending_approval', // Menunggu ACC Admin (Online)
                'approved',         // Di-ACC, menunggu bayar
                'rejected',         // Ditolak Admin
                'awaiting_payment', // Upload bukti pembayaran
                'paid',             // POS langsung masuk ke sini, Online jika valid
                'shipping',         // Proses kirim
                'completed'         // Selesai
            ])->default('pending_approval');

            // Approval & Payment
            $table->text('rejection_reason')->nullable(); // Alasan batal wajib jika ditolak
            $table->string('payment_proof_path')->nullable(); // Path gambar bukti pembayaran

            // Relasi Pengiriman Internal
            $table->foreignId('courier_id')->nullable()->constrained('couriers')->onDelete('set null');

            $table->decimal('grand_total', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
