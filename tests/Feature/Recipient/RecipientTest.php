<?php

namespace Tests\Feature\Recipient;

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
        Recipient::factory()->count(3)->create();

        $response = $this->get(route('recipient.get-all'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        // TODO: проверить получателей в массиве recipients
    }

    public function test_get_active()
    {
        Recipient::factory()->count(3)->create();

        $response = $this->get(route('recipient.get-active'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        // TODO: проверить получателей в массиве recipients
    }

    public function test_get_active_empty_list()
    {
        Recipient::factory()->inactive()->count(3)->create();

        $response = $this->get(route('recipient.get-active'));
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        // TODO: проверить получателей в массиве recipients
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
}
