<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InMemoryUserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('users', [\App\Http\Controllers\UserDetailsController::class, 'index']);
Route::post('users', [\App\Http\Controllers\UserDetailsController::class, 'store']);
Route::get('users/{id}', [\App\Http\Controllers\UserDetailsController::class, 'show']);
Route::put('users/{id}', [\App\Http\Controllers\UserDetailsController::class, 'update']);
Route::delete('users/{id}', [\App\Http\Controllers\UserDetailsController::class, 'destroy']);
