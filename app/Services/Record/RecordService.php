<?php

namespace App\Services\Record;

use App\Models\Channel;
use App\Models\Recipient;
use App\Models\Record;
use App\Repositories\RecordRepository;
use App\Services\Channel\ChannelService;
use App\Services\Recipient\RecipientService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    ) {
        $this->channelService = $channelService;
        $this->recipientService = $recipientService;
        $this->recordRepository = $recordRepository;
    }

    public function store(Recipient $recipient, Channel $channel, $password, $data = []): Record
    {
        $channelAttached = $this->recipientService->checkChannelAttached($recipient, $channel);
        if (!$channelAttached) {
            throw new RecordException('Данный канал недоступен');
        }

        $record = DB::transaction(function () use ($data, $password) {
            $record = $this->recordRepository->store($data, $password);
            $record = $this->setDeadlineByMinutes($record, $data['minutes'] ?? null);
            return $record;
        });

        if (!$record) {
            throw new RecordException('Возникла ошибка при создании записи');
        }

        return $record;
    }

    public function setDeadlineByMinutes(Record $record, $minutes): Record
    {
        $minutes = $minutes ? (int)$minutes : Record::DEADLINE_MINUTES_DEFAULT;
        $deadline = (new \DateTime())->modify("+ $minutes minutes");
        $record->deadline_at = $deadline;
        $record->save();
        return $record;
    }

    public function sendToRecipient(Record $record, $password): void
    {
        $recipient = $record->recipient;
        $channel = $record->channel;

        $channelRecipientRecord = $this->recipientService->getChannelRecipientRecord($recipient, $channel);

        // @phpstan-ignore-next-line
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

    public function disable(Record $record): Record
    {
        $record->data = '';
        $record->is_active = 0;
        $record->save();
        return $record;
    }

    public function disableExpiredRecords(): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        Record::query()
            ->active()
            ->where('deadline_at', '<', $now)
            ->update([
                'data' => '',
                'is_active' => 0
            ]);
    }

    public function checkPassword(Record $record, string $password): bool
    {
        return Hash::check($password, $record->password);
    }

    public function getData(Record $record): string
    {
        return Crypt::decryptString($record->data);
    }
}
