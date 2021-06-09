<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class DefaultControllerTest extends TestCase
{
    public function test_default_screen_can_be_rendered()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
