<?php

namespace App\Http\Controllers\Api;

use App\Models\Paste;
use Illuminate\Http\Request;

class PasteController
{

    public function handlePasteListRetrieval(Request $request) {
        // for public pastes only.

        $perPage = min(25, max(1, $request->input('perPage', 10)));
        $pastes = Paste::query()
            ->select('pastes.*', 'users.username as creator_username')
            ->leftJoin('users', 'users.id', '=', 'pastes.creator')
            ->where('visibility', '=', 'PUBLIC')
            ->orderBy('updated_at', 'desc');

        $paginated = $pastes->paginate($perPage);

        return response()->json([
            'total' => $paginated->total(),
            'currentPage' => $paginated->currentPage(),
            'pages' => $paginated->lastPage(),
            'pastes' => $paginated->items(),
        ]);
    }

}
