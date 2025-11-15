<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price', 
        'subtotal',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

     public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
