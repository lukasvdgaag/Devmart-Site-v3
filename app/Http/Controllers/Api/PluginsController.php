<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plugins\Plugin;
use App\Models\User;
use App\Utils\WebUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PluginsController
{

    public function handlePluginRetrieval(Request $request, string|int $pluginId)
    {
        $plugin = $this->getPluginOrRespond($request, $pluginId, true);
        // $plugin responded with a response instead of a plugin, so returning that.
        if (!is_array($plugin)) return $plugin;

        $response = $plugin['plugin']->toArray();

        $latestUpdate = $plugin['plugin']->getUpdates()->first();
        $response['latest_update'] = $latestUpdate;
        if ($latestUpdate != null) {
            $response['latest_update']['file_size'] = $latestUpdate->getFileDetails();
        }

        // Reformatting the sale section to be more readable.
        $response = $this->formatSaleSection($response);
        return response()->json($response);
    }

    public function handlePluginPermissionsRetrieval(Request $request, string|int $pluginId)
    {
        $plugin = $this->getPluginOrRespond($request, $pluginId, true);
        // $plugin responded with a response instead of a plugin, so returning that.
        if (!is_array($plugin)) return $plugin;

        $hasAccess = $plugin['hasAccess'];
        $plugin = $plugin['plugin'];

        return response()->json([
            'modify' => $plugin->hasModifyAccess($request->user()),
            'download' => $hasAccess,
        ]);
    }

    /**
     * @param Request $request
     * @param string|int $pluginId
     * @param bool $withExtraFields
     * @return \Illuminate\Http\JsonResponse|array
     */
    private function getPluginOrRespond(Request $request, string|int $pluginId, bool $withExtraFields = false)
    {
        $query = Plugin::query()->select('plugins.*')->where('plugins.id', '=', $pluginId);
        if ($withExtraFields) {
            $query = $this->insertAuthorUsername($query);
            $query = $this->insertTotalDownloads($query);
            $query = $this->insertSaleInformation($query);
        }

        $plugin = $query->first();
        if ($plugin == null) {
            return response()->json([
                'error' => 'Plugin not found.'
            ], 404);
        }

        $hasAccess = $plugin->hasAccess($request->user());
        if ($plugin->custom && !$hasAccess) {
            return response()->json([
                'error' => 'You have no access to this plugin.'
            ], 401);
        }
        return ["plugin" => $plugin, "hasAccess" => $hasAccess];
    }

    public function handlePluginListRetrieval(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $query = $request->input('query', '');
        $perPage = min(20, max(1, $request->query('perPage', 10)));

        $user = $request->user();
        if ($user && $filter == 'purchased' && Auth::hasUser() && $user instanceof User) {
            $plugins = $user->getPlugins();
        } else if ($filter == 'premium') {
            $plugins = DB::query()->from('plugins')
                ->where('price', '>', 0)
                ->where('custom', '=', 0);
        } else if ($filter == 'free') {
            $plugins = DB::query()->from('plugins')
                ->where('price', '=', 0)
                ->where('custom', '=', 0);
        } else {
            $plugins = DB::query()->from('plugins')
                ->where('custom', 0);
        }

        if ($query !== '') {
            $plugins = $plugins->whereNested(function ($q) use ($query) {
                $q->where('plugins.title', 'LIKE', "%$query%")
                    ->orWhere('plugins.description', 'LIKE', "%$query%");
            });
        }

        $plugins = $plugins->select(
            'plugins.id',
            'plugins.name',
            'plugins.description',
            'plugins.title',
            'plugins.custom',
            'plugins.price',
            'plugins.created_at',
            'plugins.last_updated',
            'plugins.author',
            'plugins.logo_url',
            'plugins.banner_url',
            'plugins.categories',
        );

        // including the username of the author for each plugin.
        $plugins = $this->insertAuthorUsername($plugins);
        $plugins = $this->insertTotalDownloads($plugins);
        $plugins = $this->insertSaleInformation($plugins);

        $paginated = $plugins->paginate($perPage);
        $plugins = (new Collection($paginated->items()))
            ->map(function ($plugin) {
                return $this->formatSaleSection(json_decode(json_encode($plugin), true));
            });

        return response()->json([
            'total' => $paginated->total(),
            'currentPage' => $paginated->currentPage(),
            'pages' => $paginated->lastPage(),
            'plugins' => $plugins
        ]);
    }

    public function insertAuthorUsername($plugins)
    {
        return $plugins->join('users', 'users.id', '=', 'plugins.author')
            ->addSelect(DB::raw('users.username AS author_username'))
            ->orderBy('plugins.last_updated', 'desc');
    }

    public function insertSaleInformation($plugins)
    {
        return $plugins->leftJoin('plugin_sale', function (JoinClause $join) {
            $join->on('plugin_sale.plugin', '=', 'plugins.id')
                ->whereRaw('CURRENT_TIMESTAMP() BETWEEN plugin_sale.start_date AND COALESCE(plugin_sale.end_date, (CURRENT_TIMESTAMP() + INTERVAL 1 SECOND))')
                ->orderBy('plugin_sale.end_date', 'desc')
                ->groupBy('plugin_sale.id')
                ->limit(1);
        })->addSelect(DB::raw('plugin_sale.percentage AS sale_percentage, plugin_sale.end_date AS sale_end_date, plugin_sale.start_date AS sale_start_date'))
            ->groupBy('plugin_sale.id');
    }

    public function insertTotalDownloads($plugins)
    {
        return $plugins
            ->leftJoinSub('SELECT plugin, SUM(downloads) AS downloads FROM plugin_updates GROUP BY plugin', 'downloads', function ($join) {
                $join->on('plugins.id', '=', 'downloads.plugin');
            })
            ->addSelect(DB::raw('SUM(downloads.downloads) AS downloads'))
            ->groupBy(DB::raw('plugins.id, plugins.last_updated'));
    }

    public function handlePluginSalesRetrieval(Request $request)
    {
        $user = Controller::getUserOrRedirect($request, $request->user()->id ?? null);
        if (!($user instanceof \App\Models\User)) return $user;

        $transactions = $this->getTransactionsFromRequest($user, $request);
        $perPage = min(50, max(1, $request->query('perPage', 10)));

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

        $paginate = $transactions->paginate($perPage);
        $transactions = (new Collection($paginate->items()))->map(function ($transaction) {
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

        return response()->json([
            'total' => $paginate->total(),
            'page' => $paginate->currentPage(),
            'totalPages' => $paginate->lastPage(),
            'transactions' => $transactions
        ]);
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
        $records = max(50, min(1, $request->query('perPage', 10)));
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

    /**
     * @param $response
     * @return array|null
     */
    public function formatSaleSection($response): array|null
    {
        if ($response['sale_percentage'] != null) {
            $response['sale'] = [
                'percentage' => $response['sale_percentage'],
                'start_date' => $response['sale_start_date'],
                'end_date' => $response['sale_end_date']
            ];
        } else {
            $response['sale'] = null;
        }

        unset($response['sale_percentage']);
        unset($response['sale_start_date']);
        unset($response['sale_end_date']);
        return $response;
    }

}
