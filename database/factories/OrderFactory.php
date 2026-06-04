<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending_approval', 'approved', 'rejected', 'shipping', 'completed']);

        return [
            // Tambahkan baris invoice_number ini:
            'invoice_number' => 'INV-' . fake()->date('Ymd') . '-' . fake()->unique()->numerify('####'),

            'order_source' => fake()->randomElement(['pos', 'online']),
            'status' => $status,
            'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
            'payment_proof_path' => in_array($status, ['approved', 'shipping', 'completed']) ? 'dummy/proof.jpg' : null,

            // Tambahkan juga payment_method agar sesuai dengan migrasi terbaru kita
            'payment_method' => fake()->randomElement(['transfer', 'cod', 'hutang']),

            'grand_total' => 0, // Akan dihitung ulang oleh Seeder
        ];
    }
}
