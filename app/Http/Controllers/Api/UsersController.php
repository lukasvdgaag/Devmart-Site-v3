<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController
{

    public function handleUserSearch(Request $request, int $userId): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if ($user == null || ($user->id != $userId && $user->role != "admin")) {
            return response()->json([
                'error' => 'Not authorized'
            ], 401);
        }

        Log::error("User id: " . $userId);

        return response()->json([
            'user' => User::query()->where('id', $userId)->get()->first()
        ]);
    }

    public function handleUserUpdate(Request $request, int $userId)
    {
        Log::error("Updating user.");
        $requestUser = $request->user();
        if ($requestUser == null || ($requestUser->id != $userId && $requestUser->role != "admin")) {
            Log::error("Not authorized.");
            return response()->json([
                'error' => 'Not authorized'
            ], 401);
        }

        Validator::make($request->json()->all(), [
            'username' => ['required', 'string', 'alpha_dash', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'theme' => ['required', 'in:light,dark,system'],
            'discord_suggestion_notifications' => ['required', 'in:ALL_MESSAGES,ONLY_ADMINS,ONLY_RESPONSES,NONE']
        ])->validate();

        $json = $request->json();
        Log::error("Validated request.");


        $username = $json->get('username');
        $email = $json->get('email');
        $discord = $json->get('discord');
        $discordSuggestionNotifications = $json->get('discord_suggestion_notifications');
        $theme = $json->get('theme');

        $modelValues = [];
        $user = User::query()->where('id', $userId)->get()->first();

        $lastUsernameUpdate = Carbon::parse($user->username_changed_at);

        $canChangeUsername = $username !== $user->username && ($user->role === "admin" || $lastUsernameUpdate->isBefore(Carbon::now()->subDays(30)));
        if (!$canChangeUsername) {
            $username = $user->username;
        }

        // checking if there were no changes to discontinue the rest.
        if ($username === $user->username && $email === $user->email && $discord === $user->discord && $discordSuggestionNotifications === $user->discord_suggestion_notifications && $theme === $user->theme) {
            return response()->json([
                'user' => $user
            ]);
        }

        if ($canChangeUsername) {
            if (User::query()->where('username', '=', $username)->exists()) {
                return response()->json([
                    'errors' => [
                        'username' => ['The provided username is already used by another account.']
                    ]
                ], 422);
            }

            $modelValues['username'] = $username;
            $modelValues['username_changed_at'] = Carbon::now();
        }
        if ($email !== $user->email) {
            if (User::query()->where('email', '=', $email)->exists()) {
                return response()->json([
                    'errors' => [
                        'email' => ['The provided email is already linked to another account.']
                    ]
                ], 422);
            }

            $modelValues['email'] = $email;
            $modelValues['email_verified_at'] = null;
        }

        $modelValues['discord_suggestion_notifications'] = $discordSuggestionNotifications;
        $modelValues['theme'] = $theme;

        $user->update($modelValues);
        return response()->json([
            'user' => $user
        ]);
    }

}
