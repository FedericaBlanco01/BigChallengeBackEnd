<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class DeleteSubmissionController
{
    public function __invoke(Submission $submission): JsonResponse
    {
        $submission->delete();

        return response()->json([
            'message' => 'Submission succesfully deleted!',
        ]);
    }
}
