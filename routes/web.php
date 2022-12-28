<?php

use App\Http\Controllers\Api\PluginsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
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
        Route::get('/user', function (Request $request) {
            return new UserResource($request->user());
        });

        Route::prefix('/users')->group(function () {
            Route::get('/{userId}', [UsersController::class, 'handleUserSearch']);
            Route::put('/{userId}', [UsersController::class, 'handleUserUpdate']);

            Route::get('/{userId}/paypal', [UsersController::class, 'handleUserPayPalInformationRetrieval']);
            Route::put('/{userId}/paypal', [UsersController::class, 'handleUserPayPalInformationUpdate']);
        });

        Route::prefix('/plugins')->group(function () {
            Route::get("", [PluginsController::class, 'handlePluginListRetrieval']);

            /*
             * ?user=1
             * ?from=2021-01-01
             * ?to=2021-01-01
             * ?records=10
             * ?sum=1
             */
            Route::get('/sales', [PluginsController::class, 'handlePluginSalesRetrieval']);
            Route::get('/sales/daily', [PluginsController::class, 'handleDailyPluginSalesRetrieval']);
        });


    });
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
