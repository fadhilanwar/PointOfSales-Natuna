<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name'); // Cth: BCA, Mandiri, BRI
            $table->string('account_number');
            $table->string('account_name'); // Cth: PT Natuna Grosir Utama
            $table->boolean('is_active')->default(true); // Untuk menonaktifkan bank tertentu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
