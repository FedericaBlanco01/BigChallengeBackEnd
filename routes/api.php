<?php

use App\Http\Controllers\AddSubmissionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/registerUser', UserRegistrationController::class);
Route::post('/loginUser', LogInController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logoutUser', LogOutController::class);
    Route::post('/addSubmission', AddSubmissionController::class);
});
Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');
