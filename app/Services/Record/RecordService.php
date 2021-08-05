<?php

namespace App\Services\Record;

use App\Models\Channel;
use App\Models\Recipient;
use App\Models\Record;
use App\Repositories\RecordRepository;
use App\Services\Channel\ChannelService;
use App\Services\Recipient\RecipientService;
use Illuminate\Support\Str;

class RecordService
{
    private ChannelService $channelService;
    private RecipientService $recipientService;
    private RecordRepository $recordRepository;

    public function __construct(
        ChannelService $channelService,
        RecipientService $recipientService,
        RecordRepository $recordRepository
    )
    {
        $this->channelService = $channelService;
        $this->recipientService = $recipientService;
        $this->recordRepository = $recordRepository;
    }

    public function generatePassword(): string
    {
        return Str::random(8);
    }

    public function store(Recipient $recipient, Channel $channel, $password, $data = []): Record
    {
        $channelAttached = $this->recipientService->checkChannelAttached($recipient, $channel);
        if (!$channelAttached) {
            throw new RecordException('Данный канал недоступен');
        }

        $record = $this->recordRepository->store($data, $password);

        if (!$record) {
            throw new RecordException('Возникла ошибка при создании записи');
        }

        return $record;
    }

    public function send(Record $record, $password): void
    {
        $recipient = $record->recipient;
        $channel = $record->channel;

        $channelRecipientRecord = $this->recipientService->getChannelRecipientRecord($recipient, $channel);
        if (!$channelRecipientRecord) {
            throw new RecordException('Данный канал недоступен');
        }

        $recipientData = $channelRecipientRecord->data;

        $channelProvider = $this->channelService->makeFromModel($channel);
        if (!$channelProvider) {
            throw new RecordException('Данный провайдер недоступен');
        }

        try {
            $channelProvider->send($recipientData, "Password: {$password}");
        } catch (\Exception $e) {
            throw new RecordException('Возникла ошибка при отправке пароля');
        }
    }

}
