<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_message_can_be_created()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/messages', [
            'content' => 'test content',
        ]);

        // Messageが作成されたかどうか
        $message = Message::first();
        $this->assertEquals('test content', $message->content);

        // レスポンスが正しいかどうか
        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_message_can_not_be_created_if_content_long()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/messages', [
            'content' => $this->faker->realTextBetween(),
        ]);

        $response->assertSessionHasErrors(['content' => 'メッセージは、140文字以下にしてください。']);
    }

    public function test_message_can_not_be_created_if_guest()
    {
        $response = $this->post('/messages', [
            'content' => 'test content',
        ]);

        $response->assertStatus(403);
    }

    public function test_message_edit_screen_can_be_rendered()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->get('/messages/' . $message->id);

        $response->assertViewHas('message', $message);
        $response->assertSuccessful();
    }

    public function test_message_edit_screen_can_not_be_rendered_if_unauthorized()
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/messages/' . $message->id);

        $response->assertStatus(403);
    }

    public function test_message_edit_screen_can_not_be_rendered_if_message_not_found()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/messages/100');

        $response->assertStatus(404);
    }

    public function test_message_can_be_updated()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->post('/messages/' . $message->id, [
            'content' => 'updated content',
        ]);

        // Messageが更新されたかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertEquals('updated content', $updatedMessage->content);

        // レスポンスが正しいかどうか
        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_message_can_not_be_updated_if_content_long()
    {
        $message = Message::factory()->create();
        $response = $this->actingAs($message->user)->post('/messages/' . $message->id, [
            'content' => $this->faker->realTextBetween(),
        ]);

        // Messageが更新されていないかどうか
        $updatedMessage = Message::find($message->id);
        $this->assertNotEquals('updated content', $updatedMessage->content);

        $response->assertSessionHasErrors(['content' => 'メッセージは、140文字以下にしてください。']);
    }

    public function test_message_can_not_be_updated_if_unauthorized()
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/messages/' . $message->id, [
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
        $response = $this->actingAs($message->user)->delete('/messages/' . $message->id);

        // Messageが削除されているかどうか
        $deletedMessage = Message::find($message->id);
        $this->assertEmpty($deletedMessage);

        // レスポンスが正しいかどうか
        $response->assertStatus(302);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_message_cat_not_be_destroyed_if_unauthorized()
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/messages/' . $message->id, [
            'content' => 'updated content',
        ]);

        // Messageが更新されていないかどうか
        $deletedMessage = Message::find($message->id);
        $this->assertNotEmpty($deletedMessage);

        // レスポンスが正しいかどうか
        $response->assertStatus(403);
    }
}
