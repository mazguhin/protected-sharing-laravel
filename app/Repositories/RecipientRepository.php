<?php

namespace App\Repositories;

use App\Models\Channel;
use App\Models\ChannelRecipient;
use App\Models\Recipient;
use Illuminate\Support\Facades\Log;

class RecipientRepository
{
    public function findById(int $id): ?Recipient
    {
        return Recipient::where('id', $id)->first();
    }

    public function findActiveById(int $id): ?Recipient
    {
        return Recipient::active()
            ->where('id', $id)
            ->first();
    }

    public function findAll(): array
    {
        return Recipient::all();
    }

    public function findActive(): array
    {
        return Recipient::active()->get();
    }

    public function channelIsAttached(Recipient $recipient, Channel $channel): bool
    {
        return ChannelRecipient::query()
            ->where('recipient_id', $recipient->id)
            ->where('channel_id', $channel->id)
            ->exists();
    }

    public function create(array $fields): Recipient
    {
        try {
            return Recipient::create($fields);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'fields' => $fields,
            ]);

            throw new \Exception('An error occurred during processing. Please try again later.');
        }
    }
}
