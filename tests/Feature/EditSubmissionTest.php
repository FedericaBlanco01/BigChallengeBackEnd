<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_submission_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $submission = Submission::factory()->create([
            'patient_id'=>$user->id,
        ]);

        $response = $this->patchJson('/api/submissions/'.$submission->id.'/update', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Submission succesfully updated!',
        ]);
        $this->assertDatabaseHas('submissions', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);
    }

    public function test_doctor_edit_submission_unsuccessful()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::DOCTOR_ROLE);
        $patient = User::factory()->create();
        $patient->assignRole(User::PATIENT_ROLE);
        Sanctum::actingAs($user);

        $submission = Submission::factory()->create([
            'patient_id'=>$patient->id,
        ]);

        $response = $this->patchJson('/api/submissions/'.$submission->id.'/update', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('submissions', [
            'weight' => 60,
            'height' => 173,
            'observations' => 'diabetes type 1',
            'symptoms' => 'headake, fatigue, runny nose',
        ]);
    }

    /**
     * @dataProvider emptyFieldProvider
     **/
    public function test_empty_field_editing_submission($data)
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $submission = Submission::factory()->create([
            'patient_id'=>$user->id,
        ]);

        $response = $this->patchJson('/api/submissions/'.$submission->id.'/update', $data);

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
