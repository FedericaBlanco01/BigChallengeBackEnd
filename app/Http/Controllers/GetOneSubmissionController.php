<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class GetOneSubmissionController
{
    public function __invoke(Submission $submission): JsonResponse
    {
        return response()->json([
            'message' => 'Submission fetched succesfully!',
            'submission'=> $submission->toArray(),
        ]);
    }
}
