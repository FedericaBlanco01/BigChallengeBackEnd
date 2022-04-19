<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AssignSubmissionController
{
    public function __invoke(Submission $submission): JsonResponse
    {
        $submission->status = Submission::INPROGRESS_STATUS;
        $submission->doctor_id = Auth::user()->id;
        $submission->save();

        return response()->json([
            'message'=>'Submission succesfully assigned!',
        ]);
    }
}
