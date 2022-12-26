<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getUserOrRedirect(Request $request, int $userId)
    {
        $user = $request->user();
        if ($user == null || ($user->id != $userId && $user->role != "admin")) {
            return response()->json([
                'error' => 'Not authorized'
            ], 401);
        }
        return $user;
    }

}
