<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetSubmissionController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        $status = $request->input('status');
        $patient = $request->input('patient');

        $submissions = Submission::with(['doctor', 'patient']);

        if ($user->hasRole(User::PATIENT_ROLE)) {
            if ($request->filled('status')) {
                $submissions = $submissions->where('status', $status);
            }

            return response()->json([
                'submissions' => $submissions->where('patient_id', $user->id)->get()->toArray(),
                'message' => 'Success! Got all the requested submissions',
            ]);
        } else {
            if (isset($patient)) {
                $submissions = $submissions->where('patient_id', $patient);
            }

            return response()->json([
                'submissions' => $submissions->where('status', Submission::PENDING_STATUS)
                                            ->orWhere(function ($query) {
                                                $query->where('doctor_id', Auth::user()->id)
                                                    ->where('status', Submission::INPROGRESS_STATUS);
                                            })->get()->toArray(),
                'message' => 'Success! Got all the requested submissions',
            ]);
        }
    }
}
