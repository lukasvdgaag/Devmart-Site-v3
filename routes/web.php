<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';

Route::prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (\Illuminate\Http\Request $request) {
            return new \App\Http\Resources\UserResource($request->user());
        });

        Route::get('/users/{userId}', [\App\Http\Controllers\Api\UsersController::class, 'handleUserSearch']);
        Route::put('/users/{userId}', [\App\Http\Controllers\Api\UsersController::class, 'handleUserUpdate']);

        Route::get('/users/{userId}/paypal', [\App\Http\Controllers\Api\UsersController::class, 'handleUserPayPalInformationRetrieval']);
        Route::put('/users/{userId}/paypal', [\App\Http\Controllers\Api\UsersController::class, 'handleUserPayPalInformationUpdate']);

    });
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
