<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_submission_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $submission = Submission::factory()->count(2)->sequence(
            [
                'patient_id'=> $user->id,
                'status'=> Submission::PENDING_STATUS,
            ],
            [
                'patient_id'=>$user->id,
                'status'=> Submission::INPROGRESS_STATUS,
            ]
        )->create();

        $one_submission = Submission::first();
        $response = $this->deleteJson('api/submissions/'.$one_submission->id.'/delete');
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Submission succesfully deleted!',
        ]);
        $this->assertDatabaseCount('submissions', 1);
    }

    public function test_doctor_delete_submission_unsuccessful()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::DOCTOR_ROLE);
        $patient = User::factory()->create();
        $patient->assignRole(User::PATIENT_ROLE);
        Sanctum::actingAs($user);

        $submission = Submission::factory()->count(2)->sequence(
            [
                'patient_id'=> $patient->id,
                'status'=> Submission::PENDING_STATUS,
            ],
            [
                'patient_id'=>$patient->id,
                'status'=> Submission::INPROGRESS_STATUS,
            ]
        )->create();

        $one_submission = Submission::first();
        $response = $this->deleteJson('api/submissions/'.$one_submission->id.'/delete');
        $response->assertForbidden();
        $this->assertDatabaseCount('submissions', 2);
    }
}
