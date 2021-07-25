<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Recipient;
use App\Repositories\RecipientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientService
{
    private $recipientRepository;

    public function __construct(RecipientRepository $recipientRepository)
    {
        $this->recipientRepository = $recipientRepository;
    }

    public function checkChannelAttached(Recipient $recipient, Channel $channel): bool
    {
        return $this->recipientRepository->channelIsAttached($recipient, $channel);
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
}
