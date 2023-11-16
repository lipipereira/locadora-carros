<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BrandController,
    CarController,
    CarModelController,
    ClientController,
    LocationController
};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('client', ClientController::class);
Route::apiResource('car', CarController::class);
Route::apiResource('model', CarModelController::class);
Route::apiResource('location', LocationController::class);
Route::apiResource('brand', BrandController::class);
