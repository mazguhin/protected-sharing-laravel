<?php

namespace App\Services\Channel\Telegram\Providers;

use App\Services\Channel\Telegram\TelegramChannelContract;

class TelegramTestProvider implements TelegramChannelContract
{
    public function send(string $recipient, string $message, $params = [])
    {
        if ($message === 'abort') {
            throw new \Exception('Возникла ошибка при отправке сообщения в Telegram');
        }
    }
}
