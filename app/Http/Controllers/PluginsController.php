<?php

namespace App\Http\Controllers;

use App\Models\Plugins\Plugin;
use App\Models\Plugins\PluginSale;
use App\Models\Plugins\PluginUpdate;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class PluginsController extends Controller
{

    static function checkPluginValidity($plugin, $needsModifyAccess = false): \App\Models\Plugins\Plugin|Redirector
    {
        $found = \App\Models\Plugins\Plugin::query()
            ->whereNested(function ($closure) use ($plugin) {
                $closure->where('id', '=', $plugin)
                    ->orWhere('name', '=', $plugin);
            })->first();

        if ($found == null) {
            return redirect('/plugins', 404);
        }

        if ($needsModifyAccess) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$found->hasModifyAccess($user)) {
                return redirect('/plugins', 401);
            }
        }

        return $found;
    }

    function deleteVersion(Request $request, $plugin) {
        $found = PluginsController::checkPluginValidity($plugin, true);
        if (!($found instanceof \App\Models\Plugins\Plugin)) return response('You have no permission to do this!', 401);

        $version = $request->get('versionId', -1);
        if ($version === -1) return response('Invalid version id entered.', 400);

        $update = $found->getUpdates()->where('id', '=', $version)->first();
        if ($update == null) return response('No update found with that id.', 400);

        $path = $update->getFilePath();
        if (file_exists($path)) {
            unlink($path);
        }
        $update->delete();

        return response('Deletion was successful.', 200);
    }

    function download($plugin, $version = "latest")
    {
        $found = PluginsController::checkPluginValidity($plugin);
        if (!($found instanceof \App\Models\Plugins\Plugin)) return $found;

        if (!$found->hasAccess(Auth::user())) {
            return response('You do not have access to this plugin.', 401);
        }

        $updates = $found->getUpdates();
        if ($version != "latest") {
            $updates = $updates->where('id', '=', $version);
        }

        $update = $updates->first();
        if ($update == null) {
            return response("The version you are requesting does not exist (v=$version, p=$plugin).", 404);
        }

        $ext = $update->file_extension;
        $path = $update->getFilePath();
        if (!file_exists($path)) {
            return redirect("/plugins/" . $found->id);
        }

        $update->downloads += 1;
        $update->save();

        return response()
            ->download($path)
            ->setContentDisposition('attachment',
                $found->name . ' v' . $update->getDisplayName() . ".$ext",
                $found->name . ".$ext");
    }

    function update(Request $request, $plugin)
    {
        $found = PluginsController::checkPluginValidity($plugin, true);
        if (!($found instanceof \App\Models\Plugins\Plugin)) return $found;

        if ($request->has(['version', 'title', 'changelog', 'file'])) {
            $update = new PluginUpdate();
            $update->version = $request->input('version');
            $update->beta_number = $request->input('beta_number') ?? 0;
            $update->plugin = $found->id;
            $update->title = $request->input('title');
            $update->changelog = $request->input('changelog');

            $file = $request->file('file');
            $fileExt = $file->getClientOriginalExtension();

            if ($fileExt === "jar" || $fileExt === "zip" || $fileExt === "rar" || $fileExt === "sk") {
                $update->file_extension = $fileExt;

                // deleting a file if the author is overriding a previous upload.
                $fullPath = $update->getFilePath();
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }

                $file->move("/home/uploads/" . $found->id, $update->getFileName());

                $update->save();

                $found->last_updated = Carbon::now();
                $found->save();
            }
        }

        return redirect('/plugins/' . $found->id);
    }

    function edit(Request $request, $plugin)
    {
        $found = PluginsController::checkPluginValidity($plugin);
        if (!($found instanceof \App\Models\Plugins\Plugin)) return $found;

        $found->title = $request->input('title');
        $found->description = $request->input('description');
        $found->categories = $request->input('categories');
        if ($found->categories != null) $found->categories = strtolower($found->categories);

        $found->price = max(0, $request->input('price'));
        $found->custom = $request->input('custom') ?? 0;

        $found->minecraft_versions = join(', ', $request->input('supported_versions') ?? []);
        $found->dependencies = $request->input('dependencies') ?? '';

        $found->spigot_link = $request->input('link-spigot');
        $found->github_link = $request->input('link-github');
        if (str_starts_with($found->github_link, '/')) {
            $found->github_link = substr($found->github_link, 1);
        }
        $found->donation_url = $request->input('link-donate');

        $found->features = $request->input('page-content');

        $found->save();

        $sale = $found->getSales()->limit(1)->first();
        if ($sale != null && Carbon::now()->isAfter($sale->end_date)) $sale = null;

        if ($request->input('sale-amount') != null && $request->input('sale-start') != null) {
            $saleAmount = $request->input('sale-amount');

            $startDate = Carbon::parse($request->input('sale-start'));
            $endDate = $request->input('sale-end');
            if ($endDate != null) $endDate = Carbon::parse($endDate);

            if ($sale == null) $sale = new PluginSale();

            $sale->plugin = $found->id;
            $sale->start_date = $startDate;
            $sale->end_date = $endDate;
            $sale->percentage = $saleAmount;
            $sale->save();
        } else {
            if ($sale != null) $sale->delete();
        }

        return redirect('/plugins/' . $found->id);
    }

    public function versions(Request $request)
    {
        $page = (int)$request->input('page', '1');
        $pluginId = $request->get('plugin', '');
        $plugin = Plugin::query()->where('id', '=', $pluginId)->first();
        if ($plugin == null) return 'none found';

        $versions = $plugin->getUpdates();
        $count = $versions->count();

        $totalPages = max(ceil($count / 15), 1);
        $page = max(min($page, $totalPages), 1);

        if ($totalPages > 1) {
            $offset = 15 * ($page - 1);
            $versions = $versions->offset($offset);
        }

        $versions = $versions->limit(15)->get();

        $updateObjects = [];
        foreach ($versions->getIterator() as $version) {
            $updateObjects[] = $version;
        }

        return View::make('plugins.plugin-page.plugin-versions')
            ->with('plugin', $plugin)
            ->with('updates', $updateObjects)
            ->with('total', $count);
    }

    public function plugins(Request $request)
    {

        $filter = $request->input('filter', 'all');
        $query = $request->input('query', '');
        $page = (int)$request->input('page', '1');

        $user = Auth::user();
        if (Auth::check() && $filter == 'purchased' && Auth::hasUser() && $user instanceof User) {
            $plugins = $user->getPlugins();
            $filter = 'purchased';
        } else if ($filter == 'premium') {
            $plugins = Plugin::query()
                ->where('price', '>', 0)
                ->where('custom', '=', 0);
            $filter = 'premium';
        } else if ($filter == 'free') {
            $plugins = Plugin::query()
                ->where('price', '=', 0)
                ->where('custom', '=', 0);
            $filter = 'free';
        } else {
            $plugins = Plugin::query()
                ->where('custom', 0);
            $filter = 'all';
        }

        if ($query !== '') {
            $plugins = $plugins->whereNested(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            });
        }

        $plugins = $plugins->orderBy('last_updated', 'desc');
        $count = $plugins->count();

        $totalPages = max(ceil($count / 6), 1);
        $page = max(min($page, $totalPages), 1);

        if ($totalPages > 1) {
            $offset = 6 * ($page - 1);
            $plugins = $plugins->offset($offset);
        }

        $plugins = $plugins->limit(6)->get();

        $pluginObjects = [];
        foreach ($plugins->getIterator() as $plugin) {
            $pluginObjects[] = $plugin->getInfoArray();
        }

        return response()->json([
            'plugins' => $pluginObjects,
            'filter' => $filter,
            'query' => $query,
            'total' => $count
        ]);

//        return View::make('plugins.plugin-list')
//            ->with('plugins', $pluginObjects)
//            ->with('filter', $filter)
//            ->with('query', $query)
//            ->with('total', $count);
    }

}
