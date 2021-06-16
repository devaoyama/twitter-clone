<?php

namespace Tests\Feature\Controllers\Api;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_can_be_returned()
    {
        Message::factory(15)->create();
        $response = $this->get('/api/messages');
        $messages = Message::orderBy('id', 'DESC')->with('user', 'likedUsers')->cursorPaginate(10);
        $this->assertEquals(
            MessageResource::collection($messages)->toJson(),
            json_encode($response->json('data'))
        );
        $response->assertSuccessful();
    }
}
