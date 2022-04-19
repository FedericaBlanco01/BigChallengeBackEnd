<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class GetOneSubmissionController
{
    public function __invoke(Submission $submission): SubmissionResource
    {
        $submission = Submission::with(['doctor', 'patient'])->find($submission->id);

        return (new \App\Http\Resources\SubmissionResource($submission))
                ->additional(['message' => 'Submission fetched succesfully!']);
    }
}
