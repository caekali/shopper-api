<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
    ];

    public function getImageUrlAttribute($value)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['name'] ?? null, function ($q, $value) {
                $q->where('name', 'like', "%$value%");
            })
            ->when($filters['slug'] ?? null, function ($q, $value) {
                $q->where('slug', $value);
            })
            ->when($filters['min_price'] ?? null, function ($q, $value) {
                $q->where('price', '>=', $value);
            })
            ->when($filters['max_price'] ?? null, function ($q, $value) {
                $q->where('price', '<=', $value);
            })
            ->when($filters['stock_gt'] ?? null, function ($q, $value) {
                $q->where('stock', '>', $value);
            });
    }


    public function cartItem() : HasMany {
        return $this->hasMany(CartItem::class);
    }
}
