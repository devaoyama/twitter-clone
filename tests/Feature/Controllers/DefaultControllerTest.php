<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefaultControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_screen_can_be_rendered()
    {
        Message::factory(15)->create();
        $response = $this->get('/');

//        $messages = Message::orderBy('id', 'DESC')->with('user')->cursorPaginate(10);
//        $response->assertViewHas('messages', $messages);

        $response->assertStatus(200);
    }
}
