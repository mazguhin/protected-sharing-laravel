<?php

namespace Tests\Feature\Record;

use App\Helpers\PasswordHelper;
use App\Models\Channel;
use App\Models\ChannelRecipient;
use App\Models\Recipient;
use App\Models\Record;
use App\Services\Channel\ChannelType;
use App\Services\Channel\Telegram\Providers\TelegramTestProvider;
use App\Services\Channel\Telegram\TelegramChannelContract;
use App\Services\Record\RecordService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    private $recipient;
    private $channel;
    private string $password;

    protected function setUp(): void
    {
        parent::setUp();

        $channelType = ChannelType::TELEGRAM;
        $this->instance(
            TelegramChannelContract::class,
            resolve(TelegramTestProvider::class),
        );

        $password = $this->password = 'testing';
        $this->partialMock(PasswordHelper::class, function(MockInterface $mock) use ($password) {
            $mock->shouldReceive('generatePassword')->andReturn($password);
        });

        $this->recipient = Recipient::factory()->create();
        $this->channel = Channel::factory([
            'name' => $channelType,
        ])->create();

        ChannelRecipient::factory([
            'data' => Str::random(),
            'recipient_id' => $this->recipient->id,
            'channel_id' => $this->channel->id,
        ])->create();
    }

    public function test_store_and_accept()
    {
        $secretData = 'secret data for recipient';

        $data = [
            'recipient_id' => $this->recipient->id,
            'channel_id' => $this->channel->id,
        ];

        $response = $this->post(route('record.store'), array_merge($data, [
            'data' => $secretData,
        ]));

        $identifier = $response->json('data.record.identifier');

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $this->assertNotEmpty($identifier);

        $this->assertDatabaseHas((new Record())->getTable(), $data);

        $response = $this->post(
            route('record.accept', ['identifier' => $identifier]),
            [
                'password' => $this->password
            ]
        );

        $responseSecretData = $response->json('data.data');
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $this->assertEquals($secretData, $responseSecretData);
    }
}
