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

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The provided credentials are incorrect.',
        ]);
    }

    /**
     * @dataProvider emptyFieldProvider
     **/
    public function test_invalid_input($user)
    {
        $user1 = User::factory()->create([
            'password'=>Hash::make('7488.Light'),
            'email'=> 'federica@lightit.io', ]);

        $response = $this->postJson('/api/loginUser', $user);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', $user);
    }

    public function emptyFieldProvider(): array
    {
        return [
            ['empty password'=>[
                'password'=> '',
                'email'=> 'federica@light.io', ]],
            ['empty email'=>[
                'password'=> '7488.Light',
                'email'=> '', ]],
            ['empty at'=>[
                'password'=> '7488.Light',
                'email'=> 'federicalightit.io', ]],
            ['empty dot'=>[
                'password'=> '7488.Light',
                'email'=> 'federica@lightitio', ]],
            ['empty string before at'=>[
                'password'=> '7488.Light',
                'email'=> '@lightit.io', ]],
            ['empty string after at'=>[
                'password'=> '7488.Light',
                'email'=> 'federica@.io', ]],
            ['empty string after dot'=>[
                'password'=> '7488.Light',
                'email'=> 'federica@lightit.', ]],
        ];
    }
}
