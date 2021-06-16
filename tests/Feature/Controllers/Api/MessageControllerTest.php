<?php

namespace Tests\Feature\Controllers\Api;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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

    public function test_message_can_be_destroyed()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->delete('/api/messages/' . $message->id);

        // Messageが削除されているかどうか
        $deletedMessage = Message::find($message->id);
        $this->assertEmpty($deletedMessage);

        // レスポンスが正しいかどうか
        $response->assertSuccessful();
    }

    public function test_message_cat_not_be_destroyed_if_unauthorized()
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/messages/' . $message->id);

        // Messageが更新されていないかどうか
        $deletedMessage = Message::find($message->id);
        $this->assertNotEmpty($deletedMessage);

        // レスポンスが正しいかどうか
        $response->assertStatus(403);
    }
}
