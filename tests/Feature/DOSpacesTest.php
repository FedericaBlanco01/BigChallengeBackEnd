<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use App\Notifications\PrescriptionUploaded;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DOSpacesTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_prescription_successfully()
    {
        $this->markTestSkipped('works on local');
        $this->seed(RolesSeeder::class);
        $patient = User::factory()->create();
        $patient->assignRole(User::PATIENT_ROLE);
        $doctor = User::factory()->create();
        $doctor->assignRole(User::DOCTOR_ROLE);
        Sanctum::actingAs($doctor);

        $submission = Submission::factory()->create([
            'patient_id'=>$patient->id,
            'doctor_id'=>$doctor->id,
            'status'=> Submission::INPROGRESS_STATUS,
        ]);
        Notification::fake();
        Storage::fake('do');

        $response = $this->postJson('/api/submissions/'.$submission->id.'/upload/prescription', [
            'doctorProfileImageFile' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'File uploaded',
        ]);
        $this->assertDatabaseHas('submissions', [
            'patient_id'=>$patient->id,
            'doctor_id'=>$doctor->id,
            'status'=> Submission::DONE_STATUS,
        ]);
        $submission->refresh();
        Storage::assertExists($submission->file_path);
        Notification::assertSentTo(
            [$patient],
            PrescriptionUploaded::class
        );
    }
}
