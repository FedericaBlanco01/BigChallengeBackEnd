<?php

use App\Http\Controllers\AddSubmissionController;
use App\Http\Controllers\AssignSubmissionController;
use App\Http\Controllers\DeleteSubmissionController;
use App\Http\Controllers\DoctorRegistrationController;
use App\Http\Controllers\EditSubmissionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\GetOneSubmissionController;
use App\Http\Controllers\GetSubmissionController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\PatientRegistrationController;
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

Route::post('/register/doctor', DoctorRegistrationController::class);
Route::post('/register/patient', PatientRegistrationController::class);
Route::post('/loginUser', LogInController::class);
Route::delete('/submissions/{submission}/delete', DeleteSubmissionController::class);
Route::get('/submissions/{submission}', GetOneSubmissionController::class);
Route::patch('/submissions/{submission}/update', EditSubmissionController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logoutUser', LogOutController::class);
    Route::post('/submissions/add', AddSubmissionController::class);
    Route::get('/submissions', GetSubmissionController::class);
    Route::patch('/submissions/{submission}/assign', AssignSubmissionController::class);
});
Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');
