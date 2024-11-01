<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('product', ProductController::class);

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

// Rute logout dengan middleware sanctum
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rute API produk dengan proteksi sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('products/search', [ProductController::class, 'searchByName']);

});