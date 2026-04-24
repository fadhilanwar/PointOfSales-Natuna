<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'vehicle_number',
        'is_active',
    ];

    // Relasi: Satu Kurir bisa membawa banyak Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
