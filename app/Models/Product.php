<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\PurchaseItem;
use App\Models\StockMutation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Pastikan category_id sudah masuk di fillable
    protected $fillable = [
        'category_id',
        'barcode',
        'name',
        'slug',
        'description',
        'image_path',
        'cost_price',
        'base_price',
        'stock',
        'low_stock_threshold',
    ];

    /**
     * Eloquent Lifecycle Hooks / Model Events
     */
    protected static function booted()
    {
        // Otomatis membuat slug sebelum produk disimpan ke database (saat create)
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        // Otomatis memperbarui slug jika nama produk diubah (saat update)
        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    /**
     * Override Route Model Binding untuk menggunakan 'slug'
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relasi ke tabel Categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi: Satu Produk bisa ada di banyak detail pesanan
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
