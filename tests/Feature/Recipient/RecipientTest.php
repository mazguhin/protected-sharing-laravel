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
