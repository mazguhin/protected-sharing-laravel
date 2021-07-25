<?php

namespace App\Http\Requests\Api\Recipient;

use App\Http\Requests\Api\BaseRequest;

class StoreRecipient extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
