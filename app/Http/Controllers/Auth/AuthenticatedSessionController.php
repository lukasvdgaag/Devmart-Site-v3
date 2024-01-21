<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->query('code')) {
                return redirect('/login?error=discord');
            }
            return \response()->json(['errors' => $e->errors()], 401);
        }

//        $request->session()->regenerate();
//        return redirect()->intended(WebUtils::redirectOrGo    Home($request, true));
//        return redirect()->intended();
        return \response()->json(['message' => 'Auth was successful.', 'user' => new UserResource(Auth::user())])->setStatusCode(200);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
