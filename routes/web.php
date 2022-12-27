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

        Route::prefix('/users')->group(function () {
            Route::get('/{userId}', [\App\Http\Controllers\Api\UsersController::class, 'handleUserSearch']);
            Route::put('/{userId}', [\App\Http\Controllers\Api\UsersController::class, 'handleUserUpdate']);

            Route::get('/{userId}/paypal', [\App\Http\Controllers\Api\UsersController::class, 'handleUserPayPalInformationRetrieval']);
            Route::put('/{userId}/paypal', [\App\Http\Controllers\Api\UsersController::class, 'handleUserPayPalInformationUpdate']);
        });

        Route::prefix('/plugins')->group(function () {
            Route::get("", [\App\Http\Controllers\Api\PluginsController::class, 'handlePluginListRetrieval']);

            /*
             * ?user=1
             * ?from=2021-01-01
             * ?to=2021-01-01
             * ?records=10
             * ?sum=1
             */
            Route::get('/sales', [\App\Http\Controllers\Api\PluginsController::class, 'handlePluginSalesRetrieval']);
            Route::get('/sales/daily', [\App\Http\Controllers\Api\PluginsController::class, 'handleDailyPluginSalesRetrieval']);
        });


    });
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
