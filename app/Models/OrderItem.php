<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}