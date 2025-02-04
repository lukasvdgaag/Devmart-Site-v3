<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController
{

    public function handleUserSearch(Request $request, int $userId): \Illuminate\Http\JsonResponse
    {
        $user = Controller::getUserOrRedirect($request, $userId);
        if (!($user instanceof User)) return $user;

        return response()->json([
            'user' => User::query()->where('id', $userId)->get()->first()
        ]);
    }

    public function handleUsernameSearch(Request $request) {
        // requiring the 'username' parameter.
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'min:2']
        ]);

        $username = $request->get('username');
        $users = User::query()
            ->where('username', 'LIKE', "$username%")
            ->limit(10)
            ->get(['id', 'username']);

        return response()->json([
            'users' => $users
        ]);
    }

    public function handleUserListRetrieval(Request $request) {
        if ($request->user() == null || $request->user()?->role !== 'admin') {
            return response()->json([
                'errors' => [
                    'user' => 'You are not allowed to access this resource.'
                ]
            ], 403);
        }

        $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
            'perPage' => ['nullable', 'integer', 'min:1', 'max:25'],
        ]);

        $query = $request->get('query', '');
        $perPage = $request->integer('perPage', 10);

        $users = User::query()
            ->where('username', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('id', '=', $query)
            ->orWhere('discord_id', '=', $query)
            ->orderByDesc('id');

        $paginated = $users->paginate($perPage, [
            'id', 'username', 'email', 'role', 'spigot', 'discord_id', 'created_at', 'updated_at'
        ]);

        return response()->json([
            'total' => $paginated->total(),
            'currentPage' => $paginated->currentPage(),
            'pages' => $paginated->lastPage(),
            'users' => $paginated->items(),
        ]);
    }

    public function handleUserPayPalInformationRetrieval(Request $request, int $userId)
    {
        $user = Controller::getUserOrRedirect($request, $userId);
        if (!($user instanceof User)) return $user;

        $paypalInformation = DB::query()
            ->selectRaw('p.name, p.surname, p.business')
            ->addSelect(DB::raw('COALESCE(p.email, u.email) AS email'))
            ->from('users', 'u')
            ->leftJoin('user_paypal AS p', 'u.id', '=', 'p.user')
            ->where('u.id', $userId)->get()->first();

        return response()->json([
            'paypal_information' => $paypalInformation
        ]);
    }

    public function handleUserPayPalInformationUpdate(Request $request, int $userId)
    {
        $requestUser = Controller::getUserOrRedirect($request, $userId);
        if (!($requestUser instanceof User)) return $requestUser;

        $json = $request->json();
        Validator::make($json->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'business' => ['nullable', 'string', 'max:100']
        ])->validate();

        $name = $json->get('name') ?? '';
        if ($name === '') $name = null;
        $surname = $json->get('surname') ?? '';
        if ($surname === '') $surname = null;
        $email = $json->get('email') ?? '';
        if ($email === '') $email = null;
        $business = $json->get('business') ?? '';
        if ($business === '') $business = null;

        DB::table('user_paypal')->updateOrInsert([
            'user' => $userId
        ], [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'business' => $business,
        ]);

        // return the updated paypal information from the database.
        return $this->handleUserPayPalInformationRetrieval($request, $userId);
    }

    public function handleUserUpdate(Request $request, int $userId)
    {
        $requestUser = Controller::getUserOrRedirect($request, $userId);
        if (!($requestUser instanceof User)) return $requestUser;

        $json = $request->json();
        Validator::make($json->all(), [
            'username' => ['required', 'string', 'alpha_dash', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'theme' => ['required', 'in:light,dark,system'],
            'discord_suggestion_notifications' => ['required', 'in:ALL_MESSAGES,ONLY_ADMINS,ONLY_RESPONSES,NONE']
        ])->validate();

        $username = $json->get('username');
        $email = $json->get('email');
        $discord = $json->get('discord_id');
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
        if ($username === $user->username && $email === $user->email && $discord === $user->discord_id && $discordSuggestionNotifications === $user->discord_suggestion_notifications && $theme === $user->theme) {
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

        if ($request->user()->role === 'admin') {
            $modelValues['discord_id'] = $discord;
        }
        $modelValues['discord_suggestion_notifications'] = $discordSuggestionNotifications;
        $modelValues['theme'] = $theme;

        $user->update($modelValues);
        return response()->json([
            'user' => $user
        ]);
    }

}
