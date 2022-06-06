<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Submission::factory()->count(8)->create([
            'status' => Submission::PENDING_STATUS,
        ]);
        Submission::factory()->count(8)->create([
            'status' => Submission::INPROGRESS_STATUS,
        ]);
        Submission::factory()->count(8)->create([
            'status' => "done",
        ]);

    }
}
