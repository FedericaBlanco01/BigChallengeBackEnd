<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class GetOneSubmissionController
{
    public function __invoke(Submission $submission): SubmissionResource
    {
        return (new \App\Http\Resources\SubmissionResource($submission))
                ->additional(['message' => 'Submission fetched succesfully!']);
    }
}
