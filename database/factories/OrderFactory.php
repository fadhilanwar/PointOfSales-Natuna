<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        // Tentukan delivery_status secara random dengan bobot realistis
        $deliveryStatus = $this->faker->randomElement([
            'pending',     // Baru masuk
            'pending',     // Diperbanyak biar lebih realistis
            'processing',
            'shipping',
            'delivered',
            'cancelled',
        ]);

        // Kalau delivery sudah 'delivered', payment_status wajar 'lunas'
        // Kalau masih 'pending'/'processing', wajar 'belum_lunas'
        $paymentStatus = in_array($deliveryStatus, ['delivered'])
            ? 'lunas'
            : 'belum_lunas';

        // Buat invoice number unik dengan format INV-YYYYMMDD-XXXX
        // Pakai timestamp random biar datanya bervariasi
        $randomDate = $this->faker->dateTimeBetween('-3 months', 'now');
        $datePrefix = $randomDate->format('Ymd');

        // Pakai uniqid biar tidak tabrakan antar factory call
        $sequence = $this->faker->unique()->numberBetween(1, 9999);
        $invoiceNumber = sprintf('INV-%s-%04d', $datePrefix, $sequence);

        return [
            // user_id diisi dari luar (di Seeder), tapi kasih default kalau berdiri sendiri
            'user_id'          => User::factory(),
            'invoice_number'   => $invoiceNumber,
            'shipping_address' => $this->faker->address(),
            'grand_total'      => 0, // Akan di-update di Seeder setelah items dibuat
            'delivery_status'  => $deliveryStatus,
            'payment_status'   => $paymentStatus,
            'created_at'       => $randomDate,
            'updated_at'       => $randomDate,
        ];
    }

    // -------------------------------------------------------
    // State Helper: Bisa dipakai di Seeder kalau mau spesifik
    // Contoh: Order::factory()->pending()->create()
    // -------------------------------------------------------
    public function pending(): static
    {
        return $this->state(fn() => [
            'delivery_status' => 'pending',
            'payment_status'  => 'belum_lunas',
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn() => [
            'delivery_status' => 'delivered',
            'payment_status'  => 'lunas',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn() => [
            'delivery_status' => 'cancelled',
            'payment_status'  => 'belum_lunas',
        ]);
    }
}