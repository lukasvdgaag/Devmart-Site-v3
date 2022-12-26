<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\WebUtils;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PluginsController
{

    public function handlePluginSalesRetrieval(Request $request)
    {
        $user = Controller::getUserOrRedirect($request, $request->user()->id ?? null);
        if (!($user instanceof \App\Models\User)) return $user;

        $transactions = $this->getTransactionsFromRequest($user, $request);

        $sum = $request->query('sum', 0);
        if ($sum == '1') {
            $sum = $transactions->sum(DB::raw('(payment_amount - payment_fee)'));

            if ($request->has(['compareFrom', 'compareTo'])) {
                $userId = $this->getIdFromUser($user, $request);
                $compareSum = $this->getTransactions($userId, $request->query('compareFrom'), $request->query('compareTo'), null, true)
                    ->sum(DB::raw('(payment_amount - payment_fee)'));
                $json = [
                    'total' => $sum,
                    'compareTotal' => $compareSum
                ];
                return response()->json($json);
            }

            return response()->json(['total' => $sum]);
        }

        $transactions = $transactions->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'plugin' => $transaction->plugin_id,
                'pluginName' => $transaction->name,
                'email' => $transaction->email,
                'userId' => $transaction->user_id,
                'date' => WebUtils::formatDate(Carbon::parse($transaction->createdtime)),
                'amount' => $transaction->amount
            ];
        });

        return response()->json($transactions);
    }

    public function handleDailyPluginSalesRetrieval(Request $request)
    {
        $user = Controller::getUserOrRedirect($request, $request->user()->id ?? null);
        if (!($user instanceof \App\Models\User)) return $user;

        $userId = $this->getIdFromUser($user, $request);

        $transactions = $this->getTransactions($userId, $request->query('from'), $request->query('to'), null, 10, true);
        $transactions->groupByRaw('DATE(createdtime)');

        return response()->json($transactions->get()->all());
    }

    /**
     * @param \App\Models\User $user
     * @param Request $request
     * @return Builder
     */
    public function getTransactionsFromRequest(\App\Models\User $user, Request $request): Builder
    {
        $userId = $this->getIdFromUser($user, $request);
        $from = $request->query('from');
        $to = $request->query('to');
        $records = $request->query('records', 10);
        $query = $request->query('query', '');

        return $this->getTransactions($userId, $from, $to, $query, $records);
    }

    /**
     * @param mixed $userId
     * @param string|null $from
     * @param string|null $to
     * @param string|null $query
     * @param int $records
     * @param bool $sum
     * @return Builder
     */
    public function getTransactions(string|int $userId, string|null $from = null, string|null $to = null, string|null $query = '', int $records = 10, bool $sum = false): Builder
    {
        $transactions = DB::table('gaagjescraft.mygcnt_payments')
            ->join('plugins', 'plugins.id', '=', 'mygcnt_payments.plugin_id')
            ->leftJoin('users', 'user_id', '=', 'users.id')
            ->where('plugins.author', '=', $userId)
            ->limit($records);

        if ($sum == "1") {
            $transactions->selectRaw('DATE(createdtime) as date, SUM(payment_amount) - SUM(payment_fee) as amount, COUNT(*) as count');
            $transactions->orderByDesc('date');
        } else {
            $transactions->selectRaw('gaagjescraft.mygcnt_payments.id AS id, plugin_id, mygcnt_payments.email, user_id, createdtime, payment_amount - payment_fee AS amount, plugins.name');
            $transactions->orderByDesc('createdtime');
        }

        if ($from !== null) {
            $transactions->where('createdtime', '>=', DB::raw("DATE('$from')"));
        }
        if ($to !== null) {
            $transactions->where('createdtime', '<=', DB::raw("DATE('$to')"));
        }
        if ($query !== '') {
            $transactions->whereNested(function (Builder $table) use ($query) {
                $table->where('mygcnt_payments.email', 'LIKE', "%$query%")
                    ->orWhere('user_id', '=', $query)
                    ->orWhere('users.username', 'LIKE', "%$query%");
            });
        }
        return $transactions;
    }

    /**
     * @param \App\Models\User $user
     * @param Request $request
     * @return array|mixed|string|null
     */
    public function getIdFromUser(\App\Models\User $user, Request $request): mixed
    {
        return $user->role === "admin" ? $request->query('user', $user->id) : $user->id;
    }

}
