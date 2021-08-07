<?php

namespace App\Http\Requests\Api\Record;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Channel;
use App\Models\Recipient;
use App\Rules\ActiveChannel;
use App\Rules\ActiveRecipient;

class StoreRecord extends BaseRequest
{
    public function rules()
    {
        return [
            'data' => 'required|max:3000',
            'minutes' => 'nullable|integer|min:1|max:4320',
            'recipient_id' => [
                'required',
                'integer',
                'exists:' . (new Recipient())->getTable() . ',id',
                new ActiveRecipient
            ],
            'channel_id' => [
                'required',
                'integer',
                'exists:' . (new Channel())->getTable() . ',id',
                new ActiveChannel
            ],
        ];
    }
}
