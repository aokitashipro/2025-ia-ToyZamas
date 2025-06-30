<?php

namespace App\Http\Resources\Toyzamas;

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
            'id' => $this->id,
            'name' => $this->name,
            'information' => $this->information,
            'price' => $this->price,
            'category_name' => $this->category->name,
            'series_name' => $this->series->name,
            'image_url' => $this->image_url,
            'stock' => $this->stock,
            'is_reserve' => $this->is_reserve,
            'release_date' => $this->release_date
        ];
    }
}
