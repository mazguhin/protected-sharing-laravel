<?php

namespace App\Http\Requests\Api\Recipient;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Channel;
use App\Models\Recipient;
use App\Rules\ActiveChannel;
use App\Rules\ActiveRecipient;

class DetachChannelFromRecipient extends BaseRequest
{
    public function rules()
    {
        return [
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
            ],
        ];
    }
}
