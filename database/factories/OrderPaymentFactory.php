<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // order_id diisi dari luar (di Seeder)
            'order_id'           => Order::factory(),
            'amount'             => $this->faker->numberBetween(50000, 500000),
            'type'               => $this->faker->randomElement(['full_payment', 'installment']),
            'payment_method'     => $this->faker->randomElement(['transfer', 'cod']),
            'payment_proof_path' => null, // Kebanyakan data dummy tidak punya bukti
            'status'             => 'pending', // Default pending
        ];
    }

    // -------------------------------------------------------
    // State Helper untuk OrderPayment
    // -------------------------------------------------------
    public function approved(): static
    {
        return $this->state(fn() => [
            'status' => 'approved',
            // Simulasi path bukti transfer yang sudah ada
            'payment_proof_path' => 'payment_proofs/dummy_proof.jpg',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn() => [
            'status' => 'rejected',
        ]);
    }

    public function transfer(): static
    {
        return $this->state(fn() => [
            'payment_method'     => 'transfer',
            'payment_proof_path' => 'payment_proofs/dummy_proof.jpg',
        ]);
    }

    public function cod(): static
    {
        return $this->state(fn() => [
            'payment_method'     => 'cod',
            'payment_proof_path' => null, // COD tidak perlu bukti
        ]);
    }
}
