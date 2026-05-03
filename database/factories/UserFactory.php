<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Default password agar proses hashing tidak berulang-ulang saat seeding (menghemat memori & waktu).
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(), // Mempertahankan kolom dari fase sebelumnya
            'address' => fake()->address(),
            'shop_name' => fake()->randomElement(['Toko ', 'Warung ', 'Grosir ']) . fake()->lastName(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user', // Default role
            'remember_token' => Str::random(10),
        ];
    }
}