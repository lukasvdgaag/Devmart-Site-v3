<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments\Order;
use App\Models\Plugins\PluginPayment;
use App\Models\User;
use App\Utils\WebUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    public function getRecentTransactions(Request $request)
    {
        $user = $this->getUser($request);
        if (!($user instanceof User)) return $user;

        $search = $request->get('search', '');

        // todo move over to new database
        $transactions = DB::table('gaagjescraft.mygcnt_payments')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->where('plugins.author', '=', $user->id)
            ->orderByDesc('createdtime')
            ->selectRaw('plugin_id, mygcnt_payments.email, user_id, createdtime, payment_amount, plugins.name')
            ->limit(10);

        if ($search !== '') {
            $transactions->whereNested(function ($table) use ($search) {
                $table->where('mygcnt_payments.email', 'LIKE', "%$search%")
                    ->orWhere('user_id', '=', $search)
                    ->orWhere('users.username', 'LIKE', "%$search%");
            });
        }

        $json = [];
        foreach ($transactions->get()->getIterator() as $transaction) {
            $json[] = [
                'plugin' => $transaction->plugin_id,
                'pluginName' => $transaction->name,
                'email' => $transaction->email,
                'userId' => $transaction->user_id,
                'date' => WebUtils::formatDate(Carbon::parse($transaction->createdtime)),
                'amount' => $transaction->payment_amount
            ];
        }

        return response()->json($json);
    }

    public function getTotalThirtyDays(Request $request)
    {
        $user = $this->getUser($request);
        if (!($user instanceof User)) return $user;

        $currentPeriodValue = DB::table('gaagjescraft.mygcnt_payments')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->where('plugins.author', '=', $user->id)
            ->whereDate('mygcnt_payments.createdtime', '>=', DB::raw('date(CURRENT_TIMESTAMP() - INTERVAL 30 DAY)'))
            ->sum('mygcnt_payments.payment_amount');

        $previousPeriodValue = DB::table('gaagjescraft.mygcnt_payments')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->where('plugins.author', '=', $user->id)
            ->whereDate('mygcnt_payments.createdtime', '>=', DB::raw('date(CURRENT_TIMESTAMP() - INTERVAL 60 DAY)'))
            ->whereDate('mygcnt_payments.createdtime', '<', DB::raw('date(CURRENT_TIMESTAMP() - INTERVAL 30 DAY)'))
            ->sum('mygcnt_payments.payment_amount');

        $json = [
            'total' => $currentPeriodValue,
            'previousTotal' => $previousPeriodValue
        ];

        $json = $this->insertDifference($json, $currentPeriodValue, $previousPeriodValue);

        return response()->json($json);
    }

    public function getLastWeekSales(Request $request)
    {
        $user = $this->getUser($request);
        if (!($user instanceof User)) return $user;

        $payments = PluginPayment::query()
            ->selectRaw('date(createdtime) AS date, SUM(payment_amount) AS total')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->where('plugins.author', '=', $user->id)
            ->whereDate('mygcnt_payments.createdtime', '>=', DB::raw('date(CURRENT_TIMESTAMP() - INTERVAL 7 DAY)'))
            ->groupByRaw('date(createdtime)')->get()->getIterator();

        $previousPeriodTotal = DB::table('gaagjescraft.mygcnt_payments')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->where('plugins.author', '=', $user->id)
            ->whereDate('mygcnt_payments.createdtime', '>=', DB::raw('date(CURRENT_TIMESTAMP() - INTERVAL 14 DAY)'))
            ->whereRaw("date(mygcnt_payments.createdtime) BETWEEN date(CURRENT_TIMESTAMP() - INTERVAL 14 DAY) AND date(CURRENT_TIMESTAMP() - INTERVAL 7 DAY)")
            ->sum('payment_amount');

        $json = [
            'total' => 0,
            'previousPeriodTotal' => $previousPeriodTotal,
            'periods' => []
        ];

        foreach ($payments as $payment) {
            $date = $payment->date;
            $total = $payment->total;

            if (!isset($json['periods'][$date])) {
                $json['periods'][$date] = [
                    'displayName' => Carbon::parse($date)->format('F d'),
                    'total' => 0
                ];
            }

            $json['periods'][$date]['total'] += $total;
            $json['total'] += $total;
        }

        $curTotal = $json['total'];
        $json = $this->insertDifference($json, $curTotal, $previousPeriodTotal);

        return response()->json($json);
    }

    public function handleOrderRetrieval(Request $request, string $orderId) {
        $user = $request->user();
        if (!($user instanceof User)) return response()->json(['error' => 'Invalid user'], 403);

        $order = Order::find($orderId);
        if ($order == null) return response()->json(['error' => 'Order not found'], 404);

        if ($order->user_id != $user->id) return response()->json(['error' => 'Unauthorized'], 403);

        return response()->json($order);
    }

    public function insertDifference($json, $curTotal, $previousPeriodTotal)
    {
        if ($previousPeriodTotal === 0) $percentageDifference = 0;
        else $percentageDifference = (($curTotal - $previousPeriodTotal) / $previousPeriodTotal) * 100;
        $json['percentageDifference'] = round($percentageDifference, 2);
        return $json;
    }

}
