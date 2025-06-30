<?php

namespace App\Http\Resources\owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'is_admin' => $this->is_admin == 1 ? '管理者' : '一般ユーザ'
        ];
    }
}
