<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Item ini masuk ke dalam nota pembelian yang mana
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Item ini mereferensikan produk apa di master data
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
