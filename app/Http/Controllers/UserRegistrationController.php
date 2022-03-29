<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserRegistrationController
{
    public function __invoke(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'email' => $request->input('email')
        ]);
        $user->save();

        return response()->json([
            'message' => 'User was created successfully!',
            'new_user' => $user,
        ]);
    }
}
