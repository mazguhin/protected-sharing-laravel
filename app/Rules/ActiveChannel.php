<?php

namespace App\Rules;

use App\Repositories\ChannelRepository;
use Illuminate\Contracts\Validation\Rule;

class ActiveChannel implements Rule
{
    public function passes($attribute, $value)
    {
        $channelRepository = new ChannelRepository();
        $channel = $channelRepository->findActiveById($value);
        return !empty($channel);
    }

    public function message()
    {
        return 'Channel is not active';
    }
}
