<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/news')->group(function () {
    Route::get('/', [NewsController::class, "index"]); //2 tham số : count (số lượng blog) và page (trang thứ x)
    Route::get('/{path}', [NewsController::class, "show"]);
    Route::get('/category/{category}', [NewsController::class, "category"]);
    Route::post('/', [NewsController::class, "create"]);
    Route::delete('/{id}', [NewsController::class, "delete"]);
    Route::put('/{id}', [NewsController::class, "update"]);
});

Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, "index"]);
    Route::get('/{id}', [CategoryController::class, "show"]);
    Route::post('/', [CategoryController::class, "create"]);
    Route::delete('/{id}', [CategoryController::class, "delete"]);
    Route::put('/{id}', [CategoryController::class, "update"]);
});
