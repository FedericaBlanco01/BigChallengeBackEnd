<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterDoctorTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully()
    {
        Notification::fake();
        $this->seed(RolesSeeder::class);

        $response = $this->postJson('/api/registerDoctor', [
            'name'=> 'Federica',
            'password'=> '7488.Light',
            'email'=> 'federica@lightit.io',
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'name'=> 'Federica',
            'email'=> 'federica@lightit.io', ]);
        $this->assertDatabaseCount('model_has_roles', 1);

        $user = User::where('email', 'federica@lightit.io')->first();
        $this->assertTrue($user->hasRole('doctor'));

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    /**
     * @dataProvider emptyFieldProvider
     **/
    public function test_empty_field_registration_user($user)
    {
        $response = $this->postJson('/api/registerDoctor', $user);

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
