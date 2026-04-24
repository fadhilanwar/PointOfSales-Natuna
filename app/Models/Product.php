<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\StockMutation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'name',
        'cost_price',
        'base_price',
        'stock',
        'low_stock_threshold',
    ];

    // Relasi: Satu Produk bisa ada di banyak detail pesanan
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Riwayat mutasi stok untuk produk ini
    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }
}
