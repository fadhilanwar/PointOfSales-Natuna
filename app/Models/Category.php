<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'slug',
        'description',
    ];

    /**
     * Eloquent Lifecycle Hooks / Model Events
     */
    protected static function booted()
    {
        // Otomatis membuat slug sebelum produk disimpan ke database (saat create)
        static::creating(function ($category) {
            $category->slug = Str::slug($category->category_name);
        });

        // Otomatis memperbarui slug jika nama produk diubah (saat update)
        static::updating(function ($category) {
            $category->slug = Str::slug($category->category_name);
        });
    }

    // Relasi: Satu kategori bisa ada di banyak produk  
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
