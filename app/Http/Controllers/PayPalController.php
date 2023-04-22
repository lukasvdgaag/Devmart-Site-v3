<?php

namespace App\Http\Controllers;

use App\Models\Payments\Order;
use App\Models\Payments\Payment;
use App\Models\Plugins\Plugin;
use App\Models\PluginUser;
use App\Models\User;
use http\Env\Request;
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
            Cache::put('paypal_access_token', $data['access_token'], $seconds = max(0, $data['expires_in'] - 10));
            return $data['access_token'];
        } else {
            Log::error('Failed to generate access token: ' . $response->body());
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

        $order = Order::create([
            'user_id' => $user->id,
            'plugin_id' => $plugin->id,
            'payment_amount' => $totalPrice,
        ]);

        return self::createOrder($order, $totalPrice, $items, $custom, $breakdown);
    }

    private static function createOrder(Order $order, float $price, array $items, string $custom, array $breakdown = null): string|null
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
                'return_url' => route('payments.return', ['order' => $order->id]),
                'cancel_url' => route('payments.cancel', ['order' => $order->id]),
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
            $order->order_id = $response->json('id');
            $order->status = $response->json('status');
            $order->save();

            return $response->json('links.1.href');
        } else {
            Log::error('Failed to create order: ' . $response->body());
        }
        return null;
    }

    public static function handlePaymentComplete(\Illuminate\Http\Request $request)
    {
        Log::error('Completing payment');
        if ($request->query('order') == null) {
            return redirect('/');
        }

        $order = Order::find($request->query('order'));

        if ($order == null || $order->status == 'COMPLETED') {
            return redirect('/');
        }

        // Capturing the order
        $response = Http::asJson()
            ->withToken(self::getAccessToken())
            ->post(self::getPaypalAddress() . '/v2/checkout/orders/' . $order->order_id . '/capture', null);

        if ($response->successful()) {
            // Updating the order status
            $order->status = $response->json('status');
            $order->save();

            Log::debug('Order capture response: ' . $response->body());

            // Logging the payment to the database
            Payment::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'plugin_id' => $order->plugin_id,
                'transaction_id' => $response->json('purchase_units.0.payments.captures.0.id'),
                'payment_amount' => $response->json('purchase_units.0.payments.captures.0.amount.value'),
                'payment_fee' => $response->json('purchase_units.0.payments.captures.0.seller_receivable_breakdown.paypal_fee.value'),
                'payment_status' => $response->json('purchase_units.0.payments.captures.0.status'),
                'email' => $response->json('payer.email_address'),
                'platform' => 'MY_GCNT',
                'verified' => true,
            ]);

            // Giving the user access to the plugin if the payment was successful
            if ($order->status == 'COMPLETED') {
                PluginUser::create([
                    'user_id' => $order->user_id,
                    'plugin_id' => $order->plugin_id,
                    'order_id' => $order->id,
                ]);
            }

            // TODO: send email to user.

            return redirect("/plugins/{$order->plugin_id}")->with('success', 'Payment completed successfully!');
        } else {
            Log::error('order capture error: ' . $response->body());
            return redirect('/')->with('error', 'Payment failed!');
        }

    }

}
