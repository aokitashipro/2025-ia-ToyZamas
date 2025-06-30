<?php

namespace App\Http\Resources\Toyzamas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservesListResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->toy->name,
            'price' => $this->toy->price,
            'image_url' => $this->toy->image_url,
            'reserve_num' => $this->reserve_num,
            'total' => $this->toy->price * $this->reserve_num,
            'reserve_date' => $this->toy->release_date,
        ];
    }
}
