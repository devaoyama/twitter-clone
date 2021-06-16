<?php

namespace Tests\Feature\Controllers\Api;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

    public function test_message_can_be_created()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/messages', [
            'content' => 'test content',
        ]);

        // Messageが作成されたかどうか
        $message = Message::first();
        $this->assertEquals('test content', $message->content);

        // レスポンスが正しいかどうか
        $response->assertSuccessful();
        $this->assertEquals(
            MessageResource::make($message)->toJson(),
            json_encode($response->json('data'))
        );
    }

    public function test_message_can_not_be_created_if_content_empty()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/messages', [
            'content' => '',
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['content' => 'メッセージは、必ず指定してください。']);
    }

    public function test_message_can_not_be_created_if_content_long()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/messages', [
            'content' => $this->faker->realTextBetween(),
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['content' => 'メッセージは、140文字以下にしてください。']);
    }

    public function test_message_can_not_be_created_if_guest()
    {
        $response = $this->post('/api/messages', [
            'content' => 'test content',
        ]);

        $response->assertStatus(302);
    }

    public function test_message_can_be_updated()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->put('/api/messages/' . $message->id, [
            'content' => 'updated content',
        ]);

        // Messageが更新されたかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertEquals('updated content', $updatedMessage->content);

        // レスポンスが正しいかどうか
        $response->assertSuccessful();
        $this->assertEquals(
            MessageResource::make($updatedMessage)->toJson(),
            json_encode($response->json('data'))
        );
    }

    public function test_message_can_not_be_updated_if_content_empty()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->put('/api/messages/' . $message->id, [
            'content' => '',
        ]);

        // Messageが更新されていないかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertNotEquals('updated content', $updatedMessage->content);

        $response->assertJsonValidationErrors(['content' => 'メッセージは、必ず指定してください。']);
        $response->assertStatus(400);
    }

    public function test_message_can_not_be_updated_if_content_long()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->put('/api/messages/' . $message->id, [
            'content' => $this->faker->realTextBetween(),
        ]);

        // Messageが更新されていないかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertNotEquals('updated content', $updatedMessage->content);

        $response->assertJsonValidationErrors(['content' => 'メッセージは、140文字以下にしてください。']);
        $response->assertStatus(400);
    }

    public function test_message_can_not_be_updated_if_unauthorized()
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/api/messages/' . $message->id, [
            'content' => 'updated content',
        ]);

        // Messageが更新されていないかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertNotEquals('updated content', $updatedMessage->content);

        // レスポンスが正しいかどうか
        $response->assertStatus(403);
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
