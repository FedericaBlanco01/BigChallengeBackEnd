<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class DoctorRegistrationController
{
    public function __invoke(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User([
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
        ]);
        $user->save();

        $user->assignRole(User::DOCTOR_ROLE);

        event(new Registered($user));

        return response()->json([
            'message' => 'Doctor was created successfully!',
        ]);
    }
}
