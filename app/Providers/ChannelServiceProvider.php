<?php

namespace App\Providers;

use App\Services\Channel\Telegram\Providers\TelegramSimpleProvider;
use App\Services\Channel\Telegram\TelegramChannelContract;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    public $bindings = [
        TelegramChannelContract::class => TelegramSimpleProvider::class,
    ];

    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }
}
