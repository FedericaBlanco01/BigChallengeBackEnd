<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogInUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_user_successfully()
    {
        $user = User::factory()->create([
            'password'=>Hash::make('7488.Light'), ]);

        $response = $this->postJson('/api/loginUser', [
            'password'=> '7488.Light',
            'email' =>  $user->email, ]);

        $response->assertSuccessful();
        $response->assertJson([
            'message'=> 'User loggedIn successfully',
            'status'=> 200,
        ]);
    }

    public function test_login_user_notfound()
    {
        $response = $this->postJson('/api/loginUser', [
            'password'=> Hash::make('7488.Light'),
            'email' =>  'federica@lightit.io', ]);

        $response->assertStatus(200);   //porque yo le mando la response onda sale bien
        $response->assertJson([
            'status'=>422,
            'message' => 'User not found',
        ]);
    }
}
