<?php

namespace App\Http\Resources\toyzamas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'toy_id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'is_selling' => $this->is_selling,
        ];
    }
}
