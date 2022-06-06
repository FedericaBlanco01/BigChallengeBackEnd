<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class PatientRegistrationController
{
    public function __invoke(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User([
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
        ]);
        $user->save();

        $user->assignRole(User::PATIENT_ROLE);

        event(new Registered($user));

        return response()->json([
            'message' => 'Patient was created successfully!',
        ]);
    }
}
