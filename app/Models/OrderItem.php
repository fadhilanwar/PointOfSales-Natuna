<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'cost_price_at_time',
        'price_at_time',
        'subtotal',
    ];

    // Relasi: Item ini bagian dari order mana?
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Item ini merepresentasikan produk master apa?
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
