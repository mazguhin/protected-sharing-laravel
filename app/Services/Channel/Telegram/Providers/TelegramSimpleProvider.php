<?php

namespace App\Services\Channel\Telegram\Providers;

use App\Services\Channel\Telegram\TelegramChannelContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TelegramSimpleProvider implements TelegramChannelContract
{
    public function send(string $recipient, string $message, $params = [])
    {
        $token = Config::get('channels.telegram.bot_token', null);
        if (!$token) {
            throw new \Exception('Отсутствует конфигурация провайдера');
        }

        $response = Http::get(
            "https://api.telegram.org/bot{$token}/sendMessage?text={$message}&chat_id={$recipient}"
        );

        if (!$response->ok()) {
            throw new \Exception('Возникла ошибка при отправке сообщения в Telegram');
        }
    }
}
