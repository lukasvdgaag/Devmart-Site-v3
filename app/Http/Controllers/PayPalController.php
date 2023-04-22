<?php

namespace App\Http\Controllers;

use App\Models\Plugins\Plugin;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalController
{

    private static function getPaypalAddress()
    {
        if (App::environment('local')) {
            return 'https://api.sandbox.paypal.com';
        }

        return 'https://api.paypal.com';
    }

    private static function getAccessToken()
    {
        if (Cache::has('paypal_access_token')) {
            return Cache::get('paypal_access_token');
        }

        Log::error(Config::get('auth.paypal.client_id'));
        Log::error(Config::get('auth.paypal.client_secret'));

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
            ->withBasicAuth(Config::get('auth.paypal.client_id'), Config::get('auth.paypal.client_secret'))
            ->asForm()
            ->post(self::getPaypalAddress() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        // caching the access token if the request was successful, and distracting 10 seconds from the expiration time to be sure that the token is not expired
        if ($response->successful()) {
            $data = $response->json();
            Log::error('successful');
            Log::error($data);

            Cache::put('paypal_access_token', $data['access_token'], $seconds = max(0, $data['expires_in'] - 10));
            return $data['access_token'];
        } else {
            Log::error('error:');
            Log::error($response->body());
            return null;
        }
    }

    public static function createPluginOrder(User $user, Plugin $plugin): string|null
    {
        if ($plugin == null) return null;

        $price = $plugin->price;

        $items = [
            [
                'name' => $plugin->title . ' (#' . $plugin->id . ')',
                'quantity' => 1,
                'description' => $plugin->name . ' v' . $plugin->version . ' by ' . $plugin->getAuthor->username . '.',
                'category' => 'DIGITAL_GOODS',
                'unit_amount' => [
                    'currency_code' => 'EUR',
                    'value' => $price,
                ]
            ]
        ];

        $breakdown = null;
        $salePart = $plugin->getSalePart();
        $breakdown = [
            'item_total' => [
                'currency_code' => 'EUR',
                'value' => $price,
            ],
            'discount' => [
                'currency_code' => 'EUR',
                'value' => $salePart,
            ]
        ];
        // todo include tax in breakdown??

        $custom = "devmart_purchase|plugin|{$plugin->id}|{$user->id}";
        $totalPrice = max(0, $price - $salePart);

        return self::createOrder($totalPrice, $items, $custom, $breakdown);
    }

    private static function createOrder(float $price, array $items, string $custom, array $breakdown = null): string|null
    {
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => $price,
                    ],
                    'custom_id' => $custom,
                    'items' => $items
                ],
            ],
            'application_context' => [
                'brand_name' => \config('app.name'),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
                'return_url' => route('payments.return'),
                'cancel_url' => route('payments.cancel'),
            ],
//            'payment_source' => [
//                'paypal' => [
//                    'experience_context' => [
//                        'brand_name' => \config('app.name'),
//                        'shipping_preference' => 'NO_SHIPPING',
//                        'user_action' => 'PAY_NOW',
//                        'return_url' => route('payments.return'),
//                        'cancel_url' => route('payments.cancel'),
//                    ]
//                ]
//            ]
        ];
        if ($breakdown != null) {
            $payload['purchase_units'][0]['amount']['breakdown'] = $breakdown;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->withToken(self::getAccessToken())
            ->post(self::getPaypalAddress() . '/v2/checkout/orders', $payload);

        if ($response->successful()) {
            return $response->json('links.1.href');
        } else {
            Log::error('error:');
            Log::error($response->body());
        }
        return null;
    }

}
