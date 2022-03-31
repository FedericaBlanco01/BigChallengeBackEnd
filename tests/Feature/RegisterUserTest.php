<?php

namespace Tests\Feature;

use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_successfully()
    {
        $this->seed(RolesSeeder::class);

        $response = $this->postJson('/api/registerUser', [
            'name'=> 'Federica',
            'password'=> '7488.Light',
            'email'=> 'federica@lightit.io',
            'role' => 'doctor',
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'name'=> 'Federica',
            'email'=> 'federica@lightit.io', ]);
        $this->assertDatabaseCount('model_has_roles', 1);
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => 1,
            'model_id' => 1,
        ]);
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
                'email'=> 'federica@light.io', ]],
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
