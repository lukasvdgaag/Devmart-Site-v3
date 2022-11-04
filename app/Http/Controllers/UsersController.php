<?php

namespace App\Http\Controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function updateUser(Request $request)
    {
        $user = $request->user();

        if ($user == null) return redirect()->route('account')->withErrors([
            'auth' => 'You are not authorized to do this.'
        ])->withInput();

        $request->validate([
            'username' => ['required', 'string', 'alpha_dash', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'discord' => ['required'],
            'theme' => ['required'],
            'discord-suggestion-notifications' => ['required']
        ]);

        $modelValues = [];
        $model = User::query()->where('id', $user->id);

        $username = $request->input('username');
        $email = $request->input('email');
        $discord = $request->input('discord');
        $discordSuggestionNotifications = $request->input('discord-suggestion-notifications');
        $theme = $request->input('theme');

        $lastUsernameUpdate = Carbon::parse($user->username_changed_at);

        $canChangeUsername = $username !== $user->username && ($user->role === "admin" || $lastUsernameUpdate->isBefore(Carbon::now()->subDays(30)));
        if (!$canChangeUsername) {
            $username = $user->username;
        }

        // checking if there were no changes to discontinue the rest.
        if ($username === $user->username && $email === $user->email && $discord === $user->discord && $discordSuggestionNotifications === $user->discord_suggestion_notifications && $theme === $user->theme) {
            return redirect()->route('account')->withInput();
        }

        if ($canChangeUsername) {
            if (User::query()->where('username', '=', $username)->exists()) {
                return redirect()->route('account')
                    ->withErrors([
                        'username' => 'The provided username is already used by another account.'
                    ])->withInput();
            }

            $modelValues['username']=$username;
            $modelValues['username_changed_at'] = Carbon::now();
        }
        if ($email !== $user->email) {
            if (User::query()->where('email', '=', $email)->exists()) {
                return redirect()->route('account')
                    ->withErrors([
                        'email' => 'The provided email is already linked to another account.'
                    ])->withInput();
            }

            $modelValues['email'] = $email;
            $modelValues['email_verified_at'] = null;
        }

        $modelValues['discord_suggestion_notifications'] = $discordSuggestionNotifications;
        $modelValues['theme'] = $theme;

        $model->update($modelValues);
        return redirect()->route('account');
    }

    public function getUsers(Request $request)
    {
        $user = $request->user();
        if ($user == null || $user->role !== "admin") return response()->json(['error' => 'You have no access!']);

        $search = $request->get('search', '');

        $users = DB::table('users')
            ->orderByDesc('id')
            ->selectRaw('id, username, email, discord')
            ->limit(20);

        if ($search !== '') {
            $users->whereNested(function ($table) use ($search) {
                $table->where('email', 'LIKE', "%$search%")
                    ->orWhere('id', '=', $search)
                    ->orWhere('username', 'LIKE', "%$search%");
            });
        }

        $json = [];
        foreach ($users->get()->getIterator() as $user) {
            $json[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'discord' => $user->discord
            ];
        }

        return response()->json($json);
    }

}
