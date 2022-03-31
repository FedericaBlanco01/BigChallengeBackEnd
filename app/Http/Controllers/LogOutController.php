<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LogOutController
{
    public function __invoke(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'User loggedOut successfully',
        ]);
    }
}
