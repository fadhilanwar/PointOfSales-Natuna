<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $costPrice = fake()->randomElement([15000, 20000, 45000, 120000]);
        $basePrice = $costPrice + fake()->randomElement([2000, 5000, 10000]); // Margin keuntungan

        // Sengaja membuat 30% probabilitas stok di bawah threshold (< 5)
        $isLowStock = fake()->boolean(30);

        // 1. Generate nama produk dan simpan ke variabel
        $productName = 'Produk ' . fake()->words(2, true);

        return [
            'barcode' => fake()->unique()->ean13(),
            'name' => $productName,

            'category_id' => Category::inRandomOrder()->first()->id ?? null,
            // 2. Generate slug dari nama yang sudah dibuat di atas
            'slug' => Str::slug($productName),

            // Opsional: Menambahkan deskripsi agar tampilan produk lebih realistis
            'description' => fake()->paragraph(),

            'cost_price' => $costPrice,
            'base_price' => $basePrice,
            'stock' => $isLowStock ? fake()->numberBetween(1, 4) : fake()->numberBetween(10, 100),
            'low_stock_threshold' => 5,

            'image_path' => null,
        ];
    }
}
