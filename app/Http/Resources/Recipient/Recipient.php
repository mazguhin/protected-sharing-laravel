<?php

namespace App\Http\Resources\Recipient;

use Illuminate\Http\Resources\Json\JsonResource;

class Recipient extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
        ];
    }
}
