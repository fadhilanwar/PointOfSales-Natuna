<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'vehicle_number',
        'is_active',
    ];

    // Casting is_active menjadi boolean agar mudah dibaca di view
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active' => true,
    ];
}