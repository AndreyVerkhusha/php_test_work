<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [UserController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

Route::group(["middleware" => "jwt.auth"], function () {
    Route::get("/users/trashed", [UserController::class, 'trashed']);
    Route::get("/users", [UserController::class, 'index']);
    Route::get("/users/{id}", [UserController::class, 'show']);
    Route::patch("/users/{id}", [UserController::class, 'update']);
    Route::get("/users/{id}/restore", [UserController::class, 'restore']);
    Route::post("/users/bulk-restore", [UserController::class, 'bulkRestore']);
    Route::delete("/users/bulk-destroy", [UserController::class, 'bulkDestroy']);
    Route::delete("/users/bulk-force", [UserController::class, 'bulkForceDestroy']);
    Route::delete("/users/{id}/force", [UserController::class, 'forceDestroy']);
    Route::delete("/users/{id}", [UserController::class, 'destroy']);
});
