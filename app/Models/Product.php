<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
        'category_id',
    ];

    public function getImageUrlAttribute($value)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }

    public function scopeFilter($query, Request $request)
    {
        return $query
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->min_price)
            )
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->max_price)
            )
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')
            );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'category_id' => (int) $this->category_id,
            'price' => (float) $this->price,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function cartItem(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
