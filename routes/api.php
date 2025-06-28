<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\Admin\GenreController;
use App\Http\Controllers\Api\Admin\DistributorController;

use App\Http\Middleware\RoleMiddleware;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Authentication routes
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// Genre routes
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{id}', [GenreController::class, 'show']);
Route::middleware('auth:api', RoleMiddleware::class . ':admin, manager')->group(function () {
    Route::post('/genres', [GenreController::class, 'store']);
    Route::put('/genres/{id}', [GenreController::class, 'update']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);
});

Route::get('/distributors', [DistributorController::class, 'index']);
Route::get('/distributors/{id}', [DistributorController::class, 'show']);
Route::middleware('auth:api', RoleMiddleware::class . ':admin, manager')->group(function () {
    Route::post('/distributors', [DistributorController::class, 'store']);
    Route::put('/distributors/{id}', [DistributorController::class, 'update']);
    Route::delete('/distributors/{id}', [DistributorController::class, 'destroy']);
});