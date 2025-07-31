<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'session_id',
        'product_id',
        'quantity',
        'extras',
        'unit_price'
    ];

    protected $casts = [
        // 'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'extras' => 'json',
        // 'created_at' => 'datetime',
        // 'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the CartItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
