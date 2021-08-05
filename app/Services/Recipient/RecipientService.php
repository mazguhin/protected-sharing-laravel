<?php

namespace App\Services\Recipient;

use App\Models\Channel;
use App\Models\ChannelRecipient;
use App\Models\Recipient;
use App\Repositories\RecipientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientService
{
    private RecipientRepository $recipientRepository;

    public function __construct(RecipientRepository $recipientRepository)
    {
        $this->recipientRepository = $recipientRepository;
    }

    public function checkChannelAttached(Recipient $recipient, Channel $channel): bool
    {
        return $this->recipientRepository->channelIsAttached($recipient, $channel);
    }

    public function getChannelRecipientRecord(Recipient $recipient, Channel $channel): ChannelRecipient
    {
        return ChannelRecipient::query()
            ->channel($channel->id)
            ->recipient($recipient->id)
            ->first();
    }

    public function attachChannel(Recipient $recipient, Channel $channel, string $data): Recipient
    {
        try {
            return DB::transaction(function () use ($recipient, $channel, $data) {
                $recipient->channels()->attach($channel->id, [
                    'data' => $data
                ]);

                return $recipient;
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'recipient' => $recipient,
                'channel' => $channel,
                'data' => $data,
            ]);

            throw new \Exception('An error occurred during processing. Please try again later.');
        }
    }

    public function detachChannel(Recipient $recipient, Channel $channel)
    {
        ChannelRecipient::where('recipient_id', $recipient->id)
            ->where('channel_id', $channel->id)
            ->delete();
    }
}
