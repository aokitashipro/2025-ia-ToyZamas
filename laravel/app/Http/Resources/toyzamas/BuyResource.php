<?php

namespace App\Http\Resources\toyzamas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sale_id' => $this->id,
            'total' => $this->items->sum('purchase_amount'),
            'items' => $this->items->map(function ($item) {
                return [
                    'toy_name' => $item->toy ? $item->toy->name : '不明',
                    'quantity' => $item->purchase_num,
                    'price' => $item->toy ? $item->toy->price : 0,
                    'subtotal' => $item->purchase_amount,
                ];
            }),
            'purchased_at' => $this->created_at,
        ];
    }
}
