<?php

namespace App\Repositories;

use App\Models\Channel;

class ChannelRepository
{
    public function findActiveById(int $id): ?Channel
    {
        return Channel::active()
            ->where('id', $id)
            ->first();
    }
}
