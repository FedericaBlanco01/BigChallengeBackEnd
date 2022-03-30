<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLogInRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LogInController
{
    public function __invoke(UserLogInRequest $request): JsonResponse
    {
        $user = User::where('email', $request->input('email'))->first();

        if (isset($user)) {
            if (Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'message' => 'User loggedIn successfully',
                    'status' => 200,
                    'token'=> $user->createToken($request->email)->plainTextToken,
                ]);
            }
        }

        return response()->json([
            'status'=>422,
            'message' => 'User not found', ]);
    }
}
