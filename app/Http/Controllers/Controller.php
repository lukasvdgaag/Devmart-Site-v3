<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Returns the generated file name.
     * @param $base64
     * @param $path
     * @return string
     */
    public static function saveBase64File($base64, $path): string
    {
        $data = explode(',', $base64);
        $data = base64_decode($data[1]);

        // rename the file to a random UUID, using the same extension from the base64 string.
        $extension = explode('/', mime_content_type($base64))[1];
        $filename = uniqid() . '.' . $extension;

        // saving the file to storage.
        Storage::makeDirectory("$path");
        Storage::put("$path/$filename", $data);

        return $filename;
    }

}
