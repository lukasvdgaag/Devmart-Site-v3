<?php

namespace App\Http\Controllers\Auth;

use App\Classes\WebUtils;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users'],
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'accept_tos' => ['required', 'accepted']
        ]);

        $userInfo = [
            'username' => $request->username,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->session()->has('discordAuthInfo')) {
            $discordInfo = $request->session()->get('discordAuthInfo');
            $userInfo['discord'] = $discordInfo->username . "#" . $discordInfo->discriminator;
            $userInfo['discord_verified'] = true;
            $userInfo['discord_id'] = $discordInfo->id;
        }

        $user = User::create($userInfo);

        event(new Registered($user));

        Auth::login($user);

        return WebUtils::redirectOrGoHome($request);
    }
}
