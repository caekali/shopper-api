<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'name' => $this->product->name,
            'quantity' => $this->quantity,
            'price' => (float)$this->price,
            'sub_total' => (float)$this->subtotal,
            'image_url' => $this->product->image_url
            
        ];
    }
}
/*
{
    "success": true,
    "message": "Success",
    "data": {
        "id": 3,
        "user_id": 1,
        "total_amount": "46464.00",
        "created_at": "2025-11-16T10:23:18.000000Z",
        "updated_at": "2025-12-05T03:32:19.000000Z",
        "items": [
            {
                "id": 4,
                "cart_id": 3,
                "product_id": 3,
                "quantity": 2,
                "price": "23232.00",
                "subtotal": "46464.00",
                "created_at": "2025-11-17T08:51:50.000000Z",
                "updated_at": "2025-12-05T03:32:19.000000Z",
                "product": {
                    "id": 3,
                    "name": "Redmi 12",
                    "slug": "redmi12",
                    "description": "Brand new redmi 12 phone",
                    "price": "23232.00",
                    "stock": 2,
                    "image_url": "http://localhost:8000/storage/products/fTILNDeeT9nMaYZiEyQYMl54p8NfNt7FWU3PuNPW.jpg",
                    "status": "active",
                    "created_at": "2025-11-14T20:57:38.000000Z",
                    "updated_at": "2025-11-17T06:49:41.000000Z"
                }
            }
        ]
    },
    "errors": null,
    "meta": null
}
*/
