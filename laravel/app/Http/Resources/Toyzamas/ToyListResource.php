<?php

namespace App\Http\Resources\Toyzamas;

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
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image_url' => $this->image_url
        ];
    }
}
