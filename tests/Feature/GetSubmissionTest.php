<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_submission_patient_without_filters_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::PATIENT_ROLE);
        Sanctum::actingAs($user);
        Submission::factory()->count(3)->create([
            'patient_id' => $user->id,
            'status'=> 'pending',
        ]);

        $response = $this->getJson('/api/getSubmission', [
            'status' => '',
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Success! Got all the requested submissions',
        ]);
        $response->assertJsonCount(3, 'submissions');
    }

    public function test_get_submission_patient_with_filter_successfully()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(User::PATIENT_ROLE);
        Sanctum::actingAs($user);

        Submission::factory()->count(4)->create([
            'patient_id' => $user->id,
            'status'=> Submission::PENDING_STATUS, ]);

        Submission::factory()->create([
            'patient_id' => $user->id,
            'status'=> Submission::INPROGRESS_STATUS, ]);

        $response = $this->getJson(
            '/api/getSubmission',
            ['status'=> Submission::INPROGRESS_STATUS]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Success! Got all the requested submissions',
        ]);
        dd($response);
        $response->assertJsonCount(1, 'submissions');
    }
}
