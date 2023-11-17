<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    BrandController,
    CarController,
    CarModelController,
    ClientController,
    LocationController
};

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('me', [AuthController::class, 'me'])->name('me');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::apiResource('client', ClientController::class);
    Route::apiResource('car', CarController::class);
    Route::apiResource('model', CarModelController::class);
    Route::apiResource('location', LocationController::class);
    Route::apiResource('brand', BrandController::class);
});
