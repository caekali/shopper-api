<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_id' => $this->id,
            'total_amount' => (float) $this->total_amount,
            'last_modified' => $this->updated_at->toISOString(),
            'items' => CartItemResource::collection($this->items),
        ];
    }
}
