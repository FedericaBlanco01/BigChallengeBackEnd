<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController
{
    public function __invoke(UserRegistrationRequest $request): JsonResponse
    {
        $user = new User([
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
        ]);
        $user->save();

        $user->assignRole($request->input('role'));

        return response()->json([
            'message' => 'User was created successfully!',
        ]);
    }
}
