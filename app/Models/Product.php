<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'category_id',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2', // Garantit un float avec 2 décimales
        'is_active' => 'boolean', // Convertit en true/false
    ];
}
