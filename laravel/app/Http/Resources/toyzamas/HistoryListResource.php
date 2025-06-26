<?php

namespace App\Http\Resources\toyzamas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sale_id' => $this->id,
            'total' => $this->items->sum('purchase_amount') . '円',
            'purchased_at' => $this->created_at->format('Y/n/j H:i'),
            'items' => $this->items->map(function ($item) {
                return [
                    'toy_id' => $item->toy_id,
                    'toy_name' => $item->toy ? $item->toy->name : '不明',
                    'image_url' => $item->image_url,
                    'quantity' => $item->purchase_num,
                    'price' => ($item->toy ? $item->toy->price : 0) . '円',
                    'subtotal' => $item->purchase_amount . '円',
                ];
            })
        ];
    }
}
