<?php

namespace Tests\Feature\Recipient;

use App\Http\Resources\Recipient\RecipientResource;
use App\Models\Recipient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    public function test_get_all()
    {
        $recipientsSource = Recipient::factory()->count(3)->create();

        $response = $this->get(route('recipient.get-all'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $recipients = $response->json('data.recipients');

        foreach ($recipients as $index => $recipient) {
            self::assertEquals(
                json_encode($recipient),
                (new RecipientResource($recipientsSource[$index]))->toJson(),
            );
        }
    }

    public function test_get_active()
    {
        $recipientsSource = Recipient::factory()->count(3)->create();

        $response = $this->get(route('recipient.get-active'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $recipients = $response->json('data.recipients');

        foreach ($recipients as $index => $recipient) {
            self::assertEquals(
                json_encode($recipient),
                (new RecipientResource($recipientsSource[$index]))->toJson(),
            );
        }
    }

    public function test_get_active_empty_list()
    {
        Recipient::factory()->inactive()->count(3)->create();

        $response = $this->get(route('recipient.get-active'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $recipients = $response->json('data.recipients');
        self::assertEmpty($recipients);
    }

    public function test_store()
    {
        $recipient = Recipient::factory()->make();
        $data = $recipient->attributesToArray();

        $response = $this->post(route('recipient.store'), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas((new Recipient())->getTable(), $data);
    }

    public function test_update_name()
    {
        $recipient = Recipient::factory()->create();
        $data = $recipient->attributesToArray();

        $newName = 'New example name';
        $response = $this->put(route('recipient.update', ['id' => $recipient->id]), [
            'name' => $newName
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas((new Recipient())->getTable(), array_merge($data, [
            'name' => $newName
        ]));
    }
}
