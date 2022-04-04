<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_submission_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/addSubmission', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Submission succesfully submitted!',
        ]);
        $this->assertDatabaseHas('submissions', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);
    }

    /**
     * @dataProvider emptyFieldProvider
     **/
    public function test_empty_field_registration_submission($data)
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/addSubmission', $data);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('submissions', $data);
    }

    public function emptyFieldProvider(): array
    {
        return [
            ['one digit weight' => [
                'weight' => 1,
                'height' => 173,
                'observations' => 'diabetes type 1',
                'symptoms' => 'headake, fatigue, runny nose', ]],
            ['more then three digit weight' => [
                'weight' => 1000,
                'height' => 173,
                'observations' => 'diabetes type 1',
                'symptoms' => 'headake, fatigue, runny nose', ]],
            ['less than 3 height' => [
                'weight' => 60,
                'height' => 1,
                'observations' => 'diabetes type 1',
                'symptoms' => 'headake, fatigue, runny nose', ]],
            ['more than 3 height' => [
                'weight' => 60,
                'height' => 1000,
                'observations' => 'diabetes type 1',
                'symptoms' => 'headake, fatigue, runny nose', ]],
            ['empty sypmtoms' => [
                'weight' => 60,
                'height' => 173,
                'observations' => 'diabetes type 1',
                'symptoms' => '', ]],
        ];
    }
}
