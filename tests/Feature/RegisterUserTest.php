<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_successfully()
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

    /**
     * @dataProvider emptyFieldProvider
     **/
    public function test_empty_field_registration_user($user)
    {
        $response = $this->postJson('/api/registerUser', $user);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', $user);
    }

    public function emptyFieldProvider(): array
    {
        return [
            ['empty name'=>[
                'name'=> '',
                'password'=> '7488.Light',
                'email'=> 'federica@lightit.io', ]],
            ['empty password'=>[
                'name'=> 'Federica',
                'password'=> '',
                'email'=> 'federica@lightit.io', ]],
            ['empty email'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> '', ]],
            ['empty at'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> 'federicalightit.io', ]],
            ['empty dot'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> 'federica@lightitio', ]],
            ['empty string before at'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> '@lightit.io', ]],
            ['empty string after at'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> 'federica@.io', ]],
            ['empty string after dot'=>[
                'name'=> 'Federica',
                'password'=> '7488.Light',
                'email'=> 'federica@lightit.', ]],
            ];
    }
}
