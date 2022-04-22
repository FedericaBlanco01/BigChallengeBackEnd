<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSubmissionRequest;
use App\Models\Submission;

class DeleteSubmissionController
{
    public function __invoke(Submission $submission, DeleteSubmissionRequest $delete)
    {
        $submission->delete();

        return response()->json([
                'message' => 'Submission succesfully deleted!',
            ]);
    }
}
