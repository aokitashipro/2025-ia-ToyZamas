<?php

namespace App\Http\Resources\owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToyListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'price' => number_format($this->price) . '円',
            'category' => $this->category->name,
            'series' => $this->series->name,
            'stock' => $this->stock . '個',
            'is_selling' => $this->is_selling == 1 ? '販売中' : '在庫なし',
            'is_reserve' => $this->is_reserve == 1 ? '予約可' : '予約不可'
        ];
    }
}
