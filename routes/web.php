<?php

use App\Http\Controllers\Api\DiscordController;
use App\Http\Controllers\Api\PasteController;
use App\Http\Controllers\Api\PluginsController;
use App\Http\Controllers\Api\SalesController;
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

            Route::get('/{userId}/pastes', [PasteController::class, 'handleUserPastesRetrieval']);

            Route::get('/{userId}/paypal', [UsersController::class, 'handleUserPayPalInformationRetrieval']);
            Route::put('/{userId}/paypal', [UsersController::class, 'handleUserPayPalInformationUpdate']);
        });

        Route::prefix('/paste')
            ->withoutMiddleware('auth:sanctum')
            ->group(function () {
                Route::get('', [PasteController::class, 'handlePasteListRetrieval']);
                Route::post('', [PasteController::class, 'handlePasteCreation']);
                Route::get('/{pasteId}', [PasteController::class, 'handlePasteRetrieval']);
                Route::put('/{pasteId}', [PasteController::class, 'handlePasteEdit']);
                Route::delete('/{pasteId}', [PasteController::class, 'handlePasteDeletion']);
            });

        Route::prefix('/plugins')->group(function () {
            Route::get("", [PluginsController::class, 'handlePluginListRetrieval'])
                ->withoutMiddleware('auth:sanctum');

            Route::get('/sales', [PluginsController::class, 'handlePluginSalesRetrieval']);
            Route::get('/sales/daily', [PluginsController::class, 'handleDailyPluginSalesRetrieval']);

            Route::get('/updates/{updateId}', [PluginsController::class, 'handlePluginUpdateRetrieval'])
                ->withoutMiddleware('auth:sanctum');

            Route::get("/{pluginId}", [PluginsController::class, 'handlePluginRetrieval'])
                ->withoutMiddleware('auth:sanctum');
            Route::put('/{pluginId}', [PluginsController::class, 'handlePluginEdit']);
            Route::post('/{pluginId}/update', [PluginsController::class, 'handlePluginUpdate']);
            Route::get('/{pluginId}/updates', [PluginsController::class, 'handlePluginUpdatesRetrieval'])
                ->withoutMiddleware('auth:sanctum');
            Route::get('/{pluginId}/sales', [PluginsController::class, 'handlePluginUpcomingSalesRetrieval']);
            Route::get('/{pluginId}/transactions', [PluginsController::class, 'handlePluginTransactionsRetrieval']);
            Route::get("/{pluginId}/permissions", [PluginsController::class, 'handlePluginPermissionsRetrieval'])
                ->withoutMiddleware('auth:sanctum');
        });

        Route::prefix('/discord')->group(function () {
            Route::get('/user/{userId}', [DiscordController::class, 'getUserInformation']);
        });

        Route::prefix('/orders')->group(function () {
            Route::get('/{orderId}', [SalesController::class, 'handleOrderRetrieval']);
        });
    });
});

Route::prefix('payments')->group(function () {
    Route::get('/callback', [\App\Http\Controllers\PayPalController::class, 'handlePaymentComplete'])->name('payments.return');
    Route::get('/cancel', [\App\Http\Controllers\PayPalController::class, 'handlePaymentCancel'])->name('payments.cancel');
});
Route::get('/payment-confirmed', fn() => view('welcome'))->name('payments.confirmed');

Route::get('/paste/{pasteId}/raw', [PasteController::class, 'handleRawPasteRetrieval'])
    ->withoutMiddleware('auth:sanctum');
Route::get('/paste/{pasteId}/download', [PasteController::class, 'handlePasteDownload'])
    ->withoutMiddleware('auth:sanctum');

Route::get('/plugins/{pluginId}/download', [PluginsController::class, 'handleDownload'])
    ->withoutMiddleware('auth:sanctum');
Route::get('/plugins/{pluginId}/download/{version}', [PluginsController::class, 'handleDownload'])
    ->withoutMiddleware('auth:sanctum');
Route::get('/plugins/{pluginId}/buy', [PluginsController::class, 'handlePluginBuy']);

Route::get('/account', function () {
    return view('welcome', ['title' => 'Devmart | Account']);
})->middleware(['auth'])->name('account');

Route::get('/{any}', function () {
    return view('welcome', ['title' => 'Yeehaw']);
})->where('any', '.*');
