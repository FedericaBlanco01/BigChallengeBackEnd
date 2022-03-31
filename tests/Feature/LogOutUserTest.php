<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogOutUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_user_successfully()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/logoutUser');
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'User loggedOut successfully',
        ]);
    }

    public function test_logout_unlogged_user()
    {
        $response = $this->postJson('/api/logoutUser');
        $response->assertStatus(401);
    }
}
