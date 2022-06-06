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
        $submissions = $submissions->when($request->filled('status'), function ($query) use ($status, $user) {
            $filterCondition = $user->hasRole(User::DOCTOR_ROLE) && $status !== Submission::PENDING_STATUS;

            $query->where('status', $status)
                ->when($filterCondition, function ($query) {
                    $query->where('doctor_id', Auth::user()->id);
                });
        }, function ($query) use ($user) {
            if ($user->hasRole(User::DOCTOR_ROLE)) {
                $query->where('status', Submission::PENDING_STATUS)
                    ->orWhere(function ($query) {
                        $query->where('doctor_id', Auth::user()->id)
                            ->where('status', Submission::INPROGRESS_STATUS);
                    });
            }
        })->when($user->hasRole(User::PATIENT_ROLE), function ($query) use ($user) {
            $query->where('patient_id', $user->id);
        }, function ($query) use ($patient) {
            if ($patient) {
                $query->where('patient_id', $patient);
            }
        });

        return response()->json([
            'submissions' => $submissions->get()->toArray(),
            'message' => 'Success! Got all the requested submissions',
        ]);
    }
}
