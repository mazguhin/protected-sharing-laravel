<?php

namespace App\Http\Resources\Recipient;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RecipientCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
