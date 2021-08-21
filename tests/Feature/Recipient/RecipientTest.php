<?php

namespace Tests\Feature\Recipient;

use App\Http\Resources\Recipient\RecipientResource;
use App\Models\Channel;
use App\Models\ChannelRecipient;
use App\Models\Recipient;
use App\Services\Channel\ChannelType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    public function test_get_all()
    {
        $count = 3;
        $recipientsSource = Recipient::factory()->count($count)->create();

        $response = $this->get(route('recipient.get-all'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $content = $response->json();
        $recipientArray = $content['data']['recipients'];
        self::assertCount($count, $recipientArray);

        foreach ($recipientsSource as $key => $recipientSourceItem) {
            self::assertEquals(
                (new RecipientResource($recipientSourceItem))->jsonSerialize(),
                $recipientArray[$key]
            );
        }
    }

    public function test_get_active()
    {
        $count = 3;
        $inactiveRecipient = Recipient::factory()->inactive()->create();
        $recipientsSource = Recipient::factory()->count($count)->create();

        $response = $this->get(route('recipient.get-active'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $content = $response->json();
        $recipientArray = $content['data']['recipients'];
        self::assertCount($count, $recipientArray);

        foreach ($recipientsSource as $key => $recipientSourceItem) {
            self::assertEquals(
                (new RecipientResource($recipientSourceItem))->jsonSerialize(),
                $recipientArray[$key]
            );
        }
    }

    public function test_store()
    {
        $recipientSource = Recipient::factory()->make();
        $data = $recipientSource->attributesToArray();

        $response = $this->post(route('recipient.store'), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas((new Recipient())->getTable(), $data);
    }

    public function test_attach_channel()
    {
        $recipientSource = Recipient::factory()->create();
        $channelSource = Channel::factory([
            'name' => ChannelType::TELEGRAM
        ])->create();

        $data = [
            'recipient_id' => $recipientSource->id,
            'channel_id' => $channelSource->id,
            'data' => Str::random(),
        ];

        $response = $this->post(route('recipient.attach-channel'), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas((new ChannelRecipient())->getTable(), $data);
    }

    public function test_detach_channel()
    {
        $recipientSource = Recipient::factory()->create();
        $channelSource = Channel::factory([
            'name' => ChannelType::TELEGRAM
        ])->create();

        $data = [
            'recipient_id' => $recipientSource->id,
            'channel_id' => $channelSource->id,
        ];

        ChannelRecipient::factory(array_merge($data, [
            'data' => Str::random(),
        ]))->create();

        $response = $this->delete(route('recipient.detach-channel'), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseMissing((new ChannelRecipient())->getTable(), $data);
    }

    public function test_update()
    {
        $recipientSource = Recipient::factory()->create();

        $data = [
            'name' => $this->faker->firstName(),
            'is_active' => false,
        ];

        $response = $this->put(
            route('recipient.update', [
                'id' => $recipientSource->id
            ]),
            $data
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas(
            (new Recipient())->getTable(),
            array_merge($recipientSource->attributesToArray(), $data)
        );
    }

    public function test_delete()
    {
        $recipientSource = Recipient::factory()->create();

        $response = $this->delete(
            route('recipient.delete', [
                'id' => $recipientSource->id
            ]),
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseMissing(
            (new Recipient())->getTable(),
            $recipientSource->attributesToArray()
        );
    }
}
