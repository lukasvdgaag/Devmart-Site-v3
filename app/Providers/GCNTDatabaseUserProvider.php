<?php

namespace App\Providers;

use Closure;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GCNTDatabaseUserProvider extends EloquentUserProvider
{
    use CreatesUserProviders;

    private $conn;
    private $table;

    public function __construct(HasherContract $hasher, $model, $connection, $table)
    {
        parent::__construct($hasher, $model);
        $this->conn = $connection;
        $this->table = $table;
    }

    public static function verifyDiscordAuth($code, $redirectUri, $scope = 'identify') {
        $payload = [
            'code' => $code,
            'client_id' => env('DISCORD_BOT_CLIENT_ID'),
            'client_secret' => env('DISCORD_BOT_CLIENT_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'scope' => $scope,
        ];

        $client = new Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);

        $discord_payload_url = "https://discord.com/api/oauth2/token";

        try {
            $response = $client->request('POST', $discord_payload_url, [
                'form_params' => $payload
            ]);

            if ($response->getStatusCode() === 200) {
                $result = json_decode($response->getBody());
                $accessToken = $result->access_token;

                $discord_users_url = "https://discord.com/api/users/@me";
                $usersResponse = $client->request('GET', $discord_users_url, [
                    'headers' => [
                        'Authorization' => "Bearer $accessToken",
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]
                ]);

                if ($usersResponse->getStatusCode() === 200) {
                    return json_decode($usersResponse->getBody());
                }

            }
        } catch (Exception) {
        }
        return null;
    }

    public static function verifyDiscordLogin($code)
    {
        return GCNTDatabaseUserProvider::verifyDiscordAuth($code, 'http://127.0.0.1:8000/login-discord');
    }

    public static function verifyDiscordRegister($code) {
        return GCNTDatabaseUserProvider::verifyDiscordAuth($code, 'http://127.0.0.1:8000/register-discord', 'identify email');
    }

    /**
     * @throws ValidationException
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // generic "user" object that will be utilized by the Guard instances.
        $query = $this->conn->table($this->table);

        $withDiscord = false;
        if (isset($credentials['code']) && $credentials['code'] && $credentials['code'] !== '') {
            $discordId = GCNTDatabaseUserProvider::verifyDiscordLogin($credentials['code'])->id;
            if (!$discordId) return;

            $query->where('discord_id', $discordId)
                ->where('discord_verified', true);
            $withDiscord = true;
        } else {
            foreach ($credentials as $key => $value) {
                if (Str::contains($key, 'password')) {
                    continue;
                }

                if (is_array($value) || $value instanceof Arrayable) {
                    $query->whereIn($key, $value);
                } elseif ($value instanceof Closure) {
                    $value($query);
                } else {
                    $query->where($key, $value);
                }
            }
        }

        // Now we are ready to execute the query to see if we have an user matching
        // the given credentials. If not, we will just return nulls and indicate
        // that there are no matching users for these given credential arrays.
        $user = $query->first();

        $genericUser = $this->getGenericUser($user);

        if ($genericUser != null && $withDiscord) {
            $genericUser->discordAuthenticated = true;
        }
        return $genericUser;
    }

    public function getGenericUser($user) {
        if (! is_null($user)) {
            return new GenericUser((array) $user);
        }
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        if (($user->discordAuthenticated ?? null) === true) {
            return true;
        }
        return parent::validateCredentials($user, $credentials);
    }

    public function getModel()
    {
        return parent::getModel(); // TODO: Change the autogenerated stub
    }

}
