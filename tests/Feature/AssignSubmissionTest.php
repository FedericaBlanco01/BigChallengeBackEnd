<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AssignSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_submission_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::DOCTOR_ROLE);
        Sanctum::actingAs($user);

        $submission = Submission::factory()->create([
                'status' => Submission::PENDING_STATUS,
            ]);

        $response = $this->patchJson('/api/submissions/'.$submission->id.'/assign');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Submission succesfully assigned!']);
        $this->assertDatabaseHas('submissions', [
                'status' => Submission::INPROGRESS_STATUS,
                'doctor_id' => $user->id,
            ]);
    }

    public function test_patient_assign_submission_unsuccessful()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::PATIENT_ROLE);
        Sanctum::actingAs($user);

        $submission = Submission::factory()->create([
            'status' => Submission::PENDING_STATUS,
        ]);

        $response = $this->patchJson('/api/submissions/'.$submission->id.'/assign');
        $response->assertForbidden();
        $this->assertDatabaseMissing('submissions', [
            'status' => Submission::INPROGRESS_STATUS,
        ]);
        $this->assertDatabaseHas('submissions', [
            'status' => Submission::PENDING_STATUS,
        ]);
    }
}
