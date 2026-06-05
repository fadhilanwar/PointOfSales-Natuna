<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Memilih bank populer di Indonesia
            'bank_name' => fake()->randomElement(['BCA', 'Mandiri', 'BRI', 'BNI', 'BSI', 'CIMB Niaga']),
            
            // Menghasilkan 10-15 digit angka acak untuk nomor rekening
            'account_number' => fake()->numerify('##########'), 
            
            // Menggunakan nama perusahaan fiktif atau nama orang
            'account_name' => fake()->company(), 
            
            // 80% kemungkinan rekening ini aktif
            'is_active' => fake()->boolean(80), 
        ];
    }
}