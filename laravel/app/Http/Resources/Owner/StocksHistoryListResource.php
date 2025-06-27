<?php

namespace App\Http\Resources\owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StocksHistoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->toy->name,
            'be_stored' => $this->be_stored,
            'stock_in' => $this->stock_in,
            'stock_out' => $this->stock_out,
            'description' => $this->description,
            'created_at' => $this->created_at->format('Y/n/j H:i')
        ];
    }
}
