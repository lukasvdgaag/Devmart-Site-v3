<?php

namespace App\Http\Controllers\Api;

use App\Models\Paste;
use App\Utils\WebUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class PasteController
{

    public function handlePasteListRetrieval(Request $request)
    {
        // for public pastes only.

        $perPage = min(25, max(1, $request->input('perPage', 10)));
        $pastes = $this->joinCreatorUsername($this->getBasicPasteInformation())
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

    public function joinCreatorUsername($builder)
    {
        return $builder->addSelect('users.username as creator_username')
            ->leftJoin('users', 'users.id', '=', 'pastes.creator');
    }

    public function getBasicPasteInformation(): \Illuminate\Database\Eloquent\Builder
    {
        return Paste::query()
            ->select('pastes.id', 'pastes.name', 'pastes.creator', 'pastes.title', 'pastes.style',
                'pastes.visibility', 'pastes.expire_at', 'pastes.created_at', 'pastes.updated_at');
    }

    public function handlePasteRetrieval(Request $request, int|string $pasteId)
    {
        $basicPasteInfo = Paste::query()
            ->select('pastes.creator', 'pastes.visibility')
            ->where('pastes.name', '=', $pasteId)
            ->first();

        if (!$basicPasteInfo) {
            return response()->json([
                'error' => 'Paste not found.',
            ], 404);
        }

        if ($basicPasteInfo->visibility === 'PRIVATE') {
            $user = $request->user();
            if (!$user || $user->id !== $basicPasteInfo->creator) {
                return response()->json([
                    'error' => 'Not authorized to view this paste.',
                ], 401);
            }
        }

        return response()->json($this->joinCreatorUsername(Paste::query()->select('pastes.*'))
            ->where('pastes.name', '=', $pasteId)
            ->first());
    }

    public function handlePasteCreation(Request $request)
    {
        if (RateLimiter::tooManyAttempts("paste-create:" . $request->ip(), $perMinute = 4)) {
            $seconds = RateLimiter::availableIn('paste-create:' . $request->ip());

            return response()->json([
                'errors' => [
                    'ratelimit' => "You are too fast! You may only create 4 pastes per minute. Create a new paste in $seconds seconds.",
                ],
            ], 429);
        }

        $request->validate([
            'title' => 'max:50',
            'lifetime' => 'nullable|string|max:10',
            'visibility' => ['nullable', Rule::in(['PUBLIC', 'UNLISTED', 'PRIVATE'])],
            'content' => 'required|string|max:16777215'
        ]);

        $user = $request->user();
        $title = $request->get('title', 'Unknown Paste');
        $selectedLifetime = $request->get('lifetime', '7d');
        $visibility = $request->get('visibility', 'PUBLIC');
        $style = $request->get('style');
        $content = $request->get('content');

        // when not logged in, default to 7 days.
        if (!$user) $selectedLifetime = '7d';
        else if ($user->role !== 'ADMIN') {
            if (!in_array($selectedLifetime, ['7d', '2w', '1m', null])) {
                $selectedLifetime = '7d';
            }
        } else {
            // user is admin
            if (!in_array($selectedLifetime, ['7d', '2w', '1m', '3m', 'never'])) {
                $selectedLifetime = '7d';
            }
        }

        // determining the expiry date from the selected lifetime.
        $expiryDate = match ($selectedLifetime) {
            '7d' => Carbon::now()->addWeeks(),
            '2w' => Carbon::now()->addWeeks(2),
            '1m' => Carbon::now()->addMonths(),
            '3m' => Carbon::now()->addMonths(3),
            'never' => null,
        };

        // when not logged in or invalid visibility, default to public.
        if (!$user || !in_array($visibility, ['PUBLIC', 'UNLISTED', 'PRIVATE'])) {
            $visibility = 'PUBLIC';
        }

        do {
            $id = WebUtils::generateRandomString();
        } while (Paste::query()->where('id', '=', $id)->exists());

        $paste = Paste::query()->create([
            'name' => $id,
            'title' => $title,
            'creator' => $user ? $user->id : null,
            'visibility' => $visibility,
            'style' => $style,
            'content' => $content,
            'expire_at' => $expiryDate,
            'lifetime' => $selectedLifetime,
        ]);

        return response()->json($paste, 201);
    }

}
