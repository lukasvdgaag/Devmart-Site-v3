<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paste;
use App\Models\User;
use App\Utils\WebUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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

    public function handleUserPastesRetrieval(Request $request, string $userId) {
        $user = Controller::getUserOrRedirect($request, $userId);
        if (!($user instanceof User)) return $user;

        $perPage = min(25, max(1, $request->input('perPage', 10)));
        $pastes = $this->joinCreatorUsername($this->getBasicPasteInformation())
            ->where('creator', '=', $userId)
            ->where('title', 'LIKE', '%' . $request->query('query', '') . '%')
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
            ->select('pastes.id', 'pastes.name', 'pastes.creator', 'pastes.title', 'pastes.style', 'pastes.lifetime',
                'pastes.visibility', 'pastes.expire_at', 'pastes.created_at', 'pastes.updated_at');
    }

    public function handleRawPasteRetrieval(Request $request, string $pasteId) {
        $paste = $this->getPasteFromRequest($request, $pasteId);
        if ($paste instanceof JsonResponse) return $paste;

        return response($paste['content'])
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function handlePasteRetrieval(Request $request, string $pasteId)
    {
        $paste = $this->getPasteFromRequest($request, $pasteId);
        if ($paste instanceof JsonResponse) return $paste;

        return response()->json($paste);
    }



    public function handlePasteDeletion(Request $request, string $pasteId) {
        $checkPerms = $this->checkPasteEditPerms($request, $pasteId);
        if ($checkPerms instanceof JsonResponse) return $checkPerms;

        Paste::query()->where('name', '=', $pasteId)->delete();
        return response('Paste deleted.', 200);
    }

    public function handlePasteDownload(Request $request, string $pasteId) {
        $paste = $this->getPasteFromRequest($request, $pasteId);
        if ($paste instanceof JsonResponse) return $paste;

        $ext = match ($paste['style']) {
            'java' => '.java',
            'YAML' => '.yml',
            'JSON' => '.json',
            'HTML' => '.html',
            'XML' => '.xml',
            'CSS' => '.css',
            'Markdown' => '.md',
            'JavaScript' => '.js',
            default => '.txt',
        };

        $fileName = $paste['title'] . (str_ends_with($paste['title'], $ext) ? '' : $ext);

        return response($paste['content'])
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName);
    }

    private function getPasteFromRequest(Request $request, string $pasteId): JsonResponse|Paste {
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
            if (!$user || ($user->id !== $basicPasteInfo->creator && $user->role !== 'admin')) {
                return response()->json([
                    'error' => 'Not authorized to view this paste.',
                ], 401);
            }
        }

        $paste = $this->joinCreatorUsername(Paste::query()->select('pastes.*'))
            ->where('pastes.name', '=', $pasteId)
            ->first();
        $paste->content = gzuncompress(utf8_decode($paste->content));
        return $paste;
    }

    private function checkForRateLimit(Request $request) {
        if (RateLimiter::tooManyAttempts("paste-create:" . $request->ip(), $perMinute = 4)) {
            $seconds = RateLimiter::availableIn('paste-create:' . $request->ip());

            return response()->json([
                'errors' => [
                    'ratelimit' => "You are too fast! You may only create/update 4 pastes per minute. Try again in $seconds seconds.",
                ],
            ], 429);
        }
        return null;
    }

    private function checkPasteEditPerms(Request $request, string $pasteId) {
        $basicPasteInfo = Paste::query()
            ->select('pastes.creator')
            ->where('pastes.name', '=', $pasteId)
            ->first();

        if (!$basicPasteInfo) {
            return response()->json([
                'error' => 'Paste not found.',
            ], 404);
        }

        $user = $request->user();
        if (!$user || ($user->id !== $basicPasteInfo->creator && $user->role !== 'admin')) {
            return response()->json([
                'error' => 'Not authorized to edit this paste.',
            ], 401);
        }
        return true;
    }

    public function handlePasteEdit(Request $request, string $pasteId) {
        $rateLimited = $this->checkForRateLimit($request);
        if ($rateLimited) return $rateLimited;

        $checkPerms = $this->checkPasteEditPerms($request, $pasteId);
        if ($checkPerms instanceof JsonResponse) return $checkPerms;

        return $this->handlePasteCreation($request, $pasteId);
    }

    public function handlePasteCreation(Request $request, string $pasteId = null)
    {
        if (!$pasteId) {
            $rateLimited = $this->checkForRateLimit($request);
            if ($rateLimited) return $rateLimited;
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
        else if ($user->role != 'admin') {
            if (!in_array($selectedLifetime, ['7d', '2w', '1m'])) {
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

        $newPaste = $pasteId == null;
        if (!$pasteId) {
            do {
                $pasteId = WebUtils::generateRandomString();
            } while (Paste::query()->where('id', '=', $pasteId)->exists());
        }

        $values = [
            'name' => $pasteId,
            'title' => $title,
            'creator' => $user ? $user->id : null,
            'visibility' => $visibility,
            'style' => $style,
            'content' => utf8_encode(gzcompress($content)),
            'expire_at' => $expiryDate,
            'lifetime' => $selectedLifetime,
        ];

        if ($newPaste) Paste::query()->create($values);
        else Paste::query()->where('name', '=', $pasteId)->update($values);

        unset($values['content']);
        return response()->json($values, $newPaste ? 201 : 200);
    }

}
