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
            'id' => $this->id,
            'name' => $this->name,
            'price' => number_format($this->price) . '円',
            'category' => $this->category->name,
            'series' => $this->series->name,
            'stock' => $this->stock . '個',
            'is_selling' => $this->is_selling == 1 ? '販売中' : '販売中止',
            'is_reserve' => $this->is_reserve == 1 ? '予約可能' : '予約不可'
        ];
    }
}
