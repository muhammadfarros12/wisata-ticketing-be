<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [\App\Http\Controllers\Api\EventController::class, 'categories']);

    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'getAllEvents']);
    Route::post('/create/events', [\App\Http\Controllers\Api\EventController::class, 'create']);
    Route::delete('/delete/event/{id}', [\App\Http\Controllers\Api\EventController::class, 'delete']);
});
