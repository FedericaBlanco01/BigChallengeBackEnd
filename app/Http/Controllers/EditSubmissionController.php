<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class EditSubmissionController
{
    public function __invoke(Submission $submission, AddSubmissionRequest $request): JsonResponse
    {
        $submission->weight = $request->input('weight');
        $submission->height = $request->input('height');
        $submission->observations = $request->input('observations');
        $submission->symptoms = $request->input('symptoms');
        $submission->save();

        return response()->json([
            'message' => 'Submission succesfully updated!',
        ]);
    }
}
