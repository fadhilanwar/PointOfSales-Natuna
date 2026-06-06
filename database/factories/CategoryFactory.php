<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Sembako', 'Minuman', 'Makanan Ringan', 'Produk Kebersihan', 'Perlengkapan Rumah']);

        return [
            'category_name' => $name, // Sesuaikan dengan nama kolom di DB (sebelumnya kamu tulis category_name)
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
