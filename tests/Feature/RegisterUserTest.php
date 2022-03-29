<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $response = $this->postJson('/api/registerUser', [
            'name'=> 'Federica',
            'password'=> '7488.Light',
            'email'=> 'federica@lightit.io', ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'name'=> 'Federica',
            'password'=> '7488.Light',
            'email'=> 'federica@lightit.io', ]);
    }
}
