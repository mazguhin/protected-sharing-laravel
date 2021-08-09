<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Services\Channel\ChannelType;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run()
    {
        Channel::factory([
           'name' => ChannelType::TELEGRAM,
           'title' => 'telegram',
        ])->create();
    }
}
