<?php

namespace Tests\Feature\Controllers\Api;

use App\Models\Like;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_can_be_stored()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->post('/api/likes', [
            'message_id' => $message->id,
        ]);

        $like = Like::where('user_id', $message->user_id)->where('message_id', $message->id)->first();
        $this->assertNotNull($like);

        $response->assertSimilarJson([1]);
        $response->assertSuccessful();
    }

    public function test_like_can_not_be_stored_if_message_id_empty()
    {
        $message = Message::factory()->create();
        Like::create(['user_id' => $message->user_id, 'message_id' => $message->id]);
        $response = $this->actingAs($message->user)->post('/api/likes', [
            'message_id' => '',
        ]);

        $response->assertJsonValidationErrors([
            'message_id' => 'message idは、必ず指定してください。',
        ]);
        $response->assertStatus(400);
    }

    public function test_like_can_not_be_stored_if_already_liked()
    {
        $message = Message::factory()->create();
        Like::create(['user_id' => $message->user_id, 'message_id' => $message->id]);
        $response = $this->actingAs($message->user)->post('/api/likes', [
            'message_id' => $message->id,
        ]);

        $response->assertJsonValidationErrors([
            'message_id' => '既にいいねが存在しています。',
        ]);
        $response->assertStatus(400);
    }

    public function test_like_can_be_destroyed()
    {
        $message = Message::factory()->create();
        Like::create(['user_id' => $message->user_id, 'message_id' => $message->id]);
        $response = $this->actingAs($message->user)->delete('/api/likes', [
            'message_id' => $message->id,
        ]);

        $like = Like::where('user_id', $message->user_id)->where('message_id', $message->id)->first();
        $this->assertNull($like);

        $response->assertSimilarJson([1]);
        $response->assertSuccessful();
    }

    public function test_like_can_not_be_destroyed_if_message_id_empty()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->delete('/api/likes', [
            'message_id' => '',
        ]);

        $response->assertJsonValidationErrors([
            'message_id' => 'message idは、必ず指定してください。',
        ]);
        $response->assertStatus(400);
    }

    public function test_like_can_not_be_destroyed_if_like_not_exists()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->delete('/api/likes', [
            'message_id' => $message->id,
        ]);

        $response->assertJsonValidationErrors([
            'message_id' => 'いいねが存在しません。',
        ]);
        $response->assertStatus(400);
    }
}
