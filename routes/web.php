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
            Route::get("", [PluginsController::class, 'handlePluginListRetrieval'])
                ->withoutMiddleware('auth:sanctum');

            Route::get('/sales', [PluginsController::class, 'handlePluginSalesRetrieval']);
            Route::get('/sales/daily', [PluginsController::class, 'handleDailyPluginSalesRetrieval']);


            Route::get("/{pluginId}", [PluginsController::class, 'handlePluginRetrieval'])
                ->withoutMiddleware('auth:sanctum');
            Route::put('/{pluginId}', [PluginsController::class, 'handlePluginEdit']);
            Route::post('/{pluginId}/update', [PluginsController::class, 'handlePluginUpdate']);
            Route::get('/{pluginId}/sales', [PluginsController::class, 'handlePluginUpcomingSalesRetrieval']);
            Route::get("/{pluginId}/permissions", [PluginsController::class, 'handlePluginPermissionsRetrieval'])
                ->withoutMiddleware('auth:sanctum');
        });


    });
});

Route::get('/account', function () {
    return view('welcome', ['title' => 'Devmart | Account']);
})->middleware(['auth'])->name('account');

Route::get('/{any}', function () {
    return view('welcome', ['title' => 'Yeehaw']);
})->where('any', '.*');
