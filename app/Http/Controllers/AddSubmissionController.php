<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddSubmissionController
{
    public function __invoke(SubmissionRequest $request): JsonResponse
    {
        $submission = new Submission([
            'patient_id'=>Auth::user()->id,
            'weight'=>$request->input('weight'),
            'height'=>$request->input('height'),
            'observations'=>$request->input('observations'),
            'symptoms'=>$request->input('symptoms'),
            'file_path'=>'',
            'status'=> Submission::PENDING_STATUS,
        ]);
        $submission->save();

        return response()->json([
            'message' => 'Submission succesfully submitted!',
        ]);
    }
}
