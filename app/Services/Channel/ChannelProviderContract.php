<?php

namespace App\Services\Channel;

interface ChannelProviderContract
{
    public function send(string $recipient, string $message, $params = []);
}
