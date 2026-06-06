<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Pastikan category_id sudah masuk di fillable
    protected $fillable = [
        'category_id',
        'barcode',
        'name',
        'image_path',
        'cost_price',
        'base_price',
        'stock',
        'low_stock_threshold',
    ];

    /**
     * Relasi ke tabel Categories
     * Fungsi ini yang tadi dicari oleh sistem dan menyebabkan error
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}