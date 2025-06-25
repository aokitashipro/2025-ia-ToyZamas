<?php

namespace App\Http\Resources\owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToyResource extends JsonResource
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
            'price' => $this->price,
            'stock' => $this->stock,
            'information' => $this->information,
            'img_url' => $this->img_url,
            'category_name' => $this->category->name,
            'category_pop' => $this->category->sort_order,
            'series_name' => $this->series->name,
            'series_pop' => $this->series->sort_order,
            'is_selling' => $this->is_selling == 1 ? '販売中' : '在庫なし',
            'is_reserve' => $this->is_reserve == 1 ? '予約可' : '予約不可',
            'release_date'=> $this->release_date,

            //front, ToysEditに必要なため追加
            'category_id' => $this->category_id,
            'series_id' => $this->series_id,
        ];
    }
}
