<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLogInRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LogInController
{
    public function __invoke(UserLogInRequest $request): JsonResponse
    {
        $user = User::where('email', $request->input('email'))->first();

        if (! isset($user) || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'message' => 'User loggedIn successfully',
            'status' => 200,
            'token' => $user->createToken($request->input('email'))->plainTextToken,
        ]);
    }
}
