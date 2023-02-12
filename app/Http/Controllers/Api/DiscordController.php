<?php

namespace App\Http\Controllers\Api;

class DiscordController
{

    function getUserInformation(\Illuminate\Http\Request $request, $userId) {
        $url = "https://discord.com/api/v9/users/$userId";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bot " . env('DISCORD_BOT_TOKEN'),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Execute the cURL request
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json([
                'error' => curl_error($ch)
            ], 500);
        }
        curl_close($ch);

        return response()->json(json_decode($response));
    }

}
