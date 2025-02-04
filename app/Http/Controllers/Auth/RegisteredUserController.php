<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DiscordUserAuthInfo;
use App\Models\User;
use App\Providers\GCNTDatabaseUserProvider;
use App\Utils\WebUtils;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    private function storeDiscordAuthInfo($discordInfo): DiscordUserAuthInfo
    {
        $authInfo = new DiscordUserAuthInfo();
        $authInfo->fill([
            'token' => WebUtils::generateRandomString(16),
            'discord_id' => $discordInfo->id,
            'discord_username' => $discordInfo->username,
            'discord_discriminator' => $discordInfo->discriminator,
            'discord_email' => $discordInfo->email,
            'discord_verified' => $discordInfo->verified,
        ]);

        $authInfo->save();

        return $authInfo;
    }

    public function relinkDiscordUser(Request $request)
    {
        if ($request->has('error')) {
            if ($request->get('error') === 'access_denied') return redirect('/account?discord_error=cancel');

            return redirect('/account?discord_error=invalid_request');
        }

        if (!$request->has('code')) {
            return redirect('/account?discord_error=invalid_request');
        }

        $verify = GCNTDatabaseUserProvider::verifyDiscordAuth($request->get('code'), 'http://127.0.0.1:8000/link-discord');
        if (!$verify) {
            return redirect('/account?discord_error=invalid_request');
        }

        $user = $request->user();
        $foundUsersWithId = User::query()
            ->where('discord_id', '=', $verify->id)
            ->where('id', '!=', $user->id)
            ->first();

        if ($foundUsersWithId) {
            return redirect('/account?discord_error=discord_in_use');
        }

        $user->discord_id = $verify->id;
        $user->discord_verified = true;
        $user->save();

        return redirect('/account?discord_success=success');
    }

    public function storeDiscordUser(Request $request)
    {
        if (!$request->has('code')) {
            return redirect('/register?discord_error=invalid_request');
        }

        $verify = GCNTDatabaseUserProvider::verifyDiscordRegister($request->get('code'));
        if ($verify) {
            if (!$verify->verified) {
                return redirect('/register?discord_error=email_not_verified');
            }

            $existingUser = User::where('email', $verify->email)->first();
            if ($existingUser) {
                return redirect('/register?discord_error=email_in_use');
            }
            $existingUser = User::where('discord_id', $verify->id)->first();
            if ($existingUser) {
                return redirect('/register?discord_error=discord_in_use');
            }

            $existingUser = User::where('username', $verify->username)->first();
            $isAlphaNumeric = !ctype_alnum($verify->username);
            if ($existingUser || $isAlphaNumeric) {
                $storedInfo = $this->storeDiscordAuthInfo($verify);

                $redirectUri = '/register?discord_error=username_in_use&username=' . urlencode($verify->username) . '&email=' . urlencode($verify->email) . '&discord_auth_token=' . urlencode($storedInfo->token);
                if ($isAlphaNumeric) $redirectUri .= '&username=' . urlencode($verify->username);

                return redirect($redirectUri);
            }

            $createdUser = User::create([
                'username' => $verify->username,
                'email' => $verify->email,
                'discord_id' => $verify->id,
                'discord_verified' => true,
                'password' => null,
            ]);
            if (!$createdUser) {
                return redirect('/register?discord_error=creation_failed');
            }

            event(new Registered($createdUser));
            Auth::login($createdUser);

            return redirect('/');
        }
        return redirect('/register')->withErrors(['discord' => 'Failed to create an account using Discord.']);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // TODO: check if the user
        $request->validate([
            'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'accept_tos' => ['required', 'accepted']
        ]);

        $userInfo = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $discordInfo = null;
        if ($request->has('discord_auth_token')) {
            $discordInfo = DiscordUserAuthInfo::where('token', $request->get('discord_auth_token'))->first();
            if ($discordInfo) {
                $userInfo['discord_verified'] = true;
                $userInfo['discord_id'] = $discordInfo->discord_id;
            }
        }

        $user = User::create($userInfo);
        if (!$user) {
            return response()->json([
                'errors' => [
                    'general' => 'Failed to create user.'
                ]
            ]);
        }

        if ($discordInfo) $discordInfo->delete();

        event(new Registered($user));
        Auth::login($user);

        return response()->json($user, 201);
    }
}
