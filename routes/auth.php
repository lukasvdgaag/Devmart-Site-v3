<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Route;

//Route::prefix('api')->group(function () {
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::get('/users/auth', function (Request $request) {
//            return $request->user();
//        });
//    });
//});

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');

        Route::prefix('discord')->group(function () {
            Route::get('register', function () {
                $clientId = \config('auth.discord.client_id');
                $redirectUrl = urlencode(\config('app.url') . '/register-discord');
                $discordUrl = "https://discord.com/api/oauth2/authorize?client_id=$clientId&redirect_uri=$redirectUrl&response_type=code&scope=identify%20email";
                return redirect($discordUrl);
            });
            Route::get('login', function () {
                $clientId = \config('auth.discord.client_id');
                $redirectUrl = urlencode(\config('app.url') . '/login-discord');
                $discordUrl = "https://discord.com/api/oauth2/authorize?client_id=$clientId&redirect_uri=$redirectUrl&response_type=code&scope=identify";
                return redirect($discordUrl);
            });
            Route::get('link', function (\Illuminate\Http\Request $request) {
                if ($request->has('code') || $request->has('error')) {
                    $cont = new RegisteredUserController();
                    return $cont->relinkDiscordUser($request);
                }

                $clientId = \config('auth.discord.client_id');
                $redirectUrl = urlencode(\config('app.url') . '/link-discord');
                $discordUrl = "https://discord.com/api/oauth2/authorize?client_id=$clientId&redirect_uri=$redirectUrl&response_type=code&scope=identify";
                return redirect($discordUrl);
            })->withoutMiddleware('guest');

            Route::get('login-callback', function (\Illuminate\Http\Request $request) {
                if ($request->has('code')) {
                    $request->merge([
                        'username' => 'unknown',
                        'password' => 'unknown'
                    ]);

                    $authCon = new AuthenticatedSessionController();
                    return $authCon->store(LoginRequest::createFrom($request));
                }
                return redirect('/login')->withErrors(['discord' => 'Failed to login with your Discord account.']);
            })->name('login.discord');
            Route::get('register-callback', function (\Illuminate\Http\Request $request) {
                if ($request->has('code')) {
                    $controller = new RegisteredUserController();
                    return $controller->storeDiscordUser($request);
                }
                return redirect('/register')->withErrors(['discord' => 'Failed to login with your Discord account.']);
            })->name('register.discord');
        });

    });
    Route::middleware('auth')->group(function () {
        Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
