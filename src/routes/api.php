<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitLikeController;
use App\Http\Controllers\DishLogController;
use App\Http\Controllers\PlaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/visits', [VisitController::class, 'store']);

    Route::post('/visits/{visit}/dish-logs', [DishLogController::class, 'store']);
    Route::patch('/visits/{visit}/publish', [VisitController::class, 'publish']);
    Route::post('/visits/{visit}/likes', [VisitLikeController::class, 'store']);
    Route::delete('/visits/{visit}/likes', [VisitLikeController::class, 'destroy']);

    Route::post('/places', [PlaceController::class, 'store']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

