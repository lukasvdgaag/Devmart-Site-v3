<?php

namespace App\Http\Controllers\Auth;

use App\Classes\WebUtils;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Nette\Schema\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     */
    public function store(LoginRequest $request)
    {
        return response("yeh no lol")->setStatusCode(400);
//        try {
//            $request->authenticate();
//        } catch (\Illuminate\Validation\ValidationException $e) {
//            return response()->setStatusCode(400)->json(['errors' => $e->errors()]);
////            return redirect('/login')->withErrors($e->errors());
//        }
//
//        $request->session()->regenerate();
////        return redirect()->intended(WebUtils::redirectOrGoHome($request, true));
//        return response("it works bruh");
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
