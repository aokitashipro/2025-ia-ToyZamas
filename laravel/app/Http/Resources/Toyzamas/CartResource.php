<?php

namespace App\Http\Resources\Toyzamas;

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
            // 'user_name' => $this->user->name,
            'cart_id' => $this->id,
            'toy_id' => $this->toy->id,
            'image_url' => $this->toy->image_url,
            'name' => $this->toy->name,
            'price' => $this->toy->price,
            'quantity' => $this->quantity,
            'stock' => $this->toy->stock,
            'total' => $this->toy->price * $this->quantity
        ];
    }
}
