<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLogInRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LogInController
{
    public function __invoke(UserLogInRequest $request): JsonResponse
    {
        $user = User::where('email', $request->input('email'))->first();

        if (isset($user)) {
            if ($user->password === $request->input('password')) {
                return response()->json([
                    'message' => 'User loggedIn successfully',
                    'status' => 200,
                ]);
            }
        }

        return response()->json([
            'status'=>422,
            'message' => 'User not found', ]);
    }
}
