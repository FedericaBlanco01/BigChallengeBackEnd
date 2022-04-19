<?php

namespace App\Http\Controllers;

use App\Models\Submission;

class GetOneSubmissionController
{
    public function __invoke(Submission $submission)
    {
        return (new \App\Http\Resources\SubmissionResource($submission))
                ->additional(['message' => 'Submission fetched succesfully!']);
    }
}
