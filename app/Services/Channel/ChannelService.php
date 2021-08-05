<?php

namespace App\Services\Channel;

use App\Models\Channel;
use App\Services\Channel\Telegram\TelegramChannelContract;

class ChannelService
{
    public function makeFromModel(Channel $channelModel): ?ChannelProviderContract
    {
        if ($channelModel->name === ChannelType::TELEGRAM) {
            return resolve(TelegramChannelContract::class);
        }

        return null;
    }
}
