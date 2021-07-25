<?php

namespace App\Repositories;

use App\Models\Channel;

class ChannelRepository
{
    public function findById(int $id): ?Channel
    {
        return Channel::where('id', $id)->first();
    }

    public function findActiveById(int $id): ?Channel
    {
        return Channel::active()
            ->where('id', $id)
            ->first();
    }
}
