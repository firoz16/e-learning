<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Routes protected by auth
Route::middleware('auth:sanctum')->group(function () {

    // Admin-only
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('courses', CourseController::class);
    });

    // Student-only
    Route::middleware('role:student')->group(function () {
        Route::get('courses',[CourseController::class,'index']);
        Route::post('/courses/enroll', [EnrollmentController::class, 'enroll']);
        Route::get('/certificate/{id}', [CertificateController::class, 'download']);
    });
});
