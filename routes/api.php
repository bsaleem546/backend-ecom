<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [\App\Http\Controllers\API\AuthController::class, 'createUser']);
Route::post('/auth/login', [\App\Http\Controllers\API\AuthController::class, 'makeLogin']);
Route::post('/auth/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group([ 'prefix' => 'admin', 'middleware' => 'auth:sanctum' ], function (){
    Route::apiResource('users', \App\Http\Controllers\API\UserController::class);
    Route::apiResource('categories', \App\Http\Controllers\API\CategoryController::class);
    Route::apiResource('brands', \App\Http\Controllers\API\BrandController::class);
});
