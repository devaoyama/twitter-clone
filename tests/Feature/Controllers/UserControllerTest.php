<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_user_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->get('/users/' . $user->id);

        $response->assertViewHas('user', $user);
        $response->assertStatus(200);
    }
}
