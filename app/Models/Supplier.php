<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;

   protected $fillable = [
    'supplier_name', // Sesuai dengan DB
    'phone',
    'address',
];
}   