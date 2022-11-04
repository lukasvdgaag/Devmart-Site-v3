<?php

namespace App\Http\Controllers;

use App\Classes\WebUtils;
use App\Models\Paste;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PasteController extends Controller
{

    public function showPaste(Request $request, $id)
    {
        if ($id == null) {
            return abort(404);
        }

        $user = $request->user();

        $paste = Paste::query()->where('id', $id)->get()->first();
        if ($paste == null || !$paste->hasAccess($user)) {
            return abort(404);
        }

        try {
            $fileStream = gzopen("/home/pastes/$id.txt.gz", "rb");
            $content = gzread($fileStream, 500000);
            gzclose($fileStream);
        } catch (Exception) {
            $content = "No content could be found for this paste.";
        }

        return view('pastes.view', [
            'paste' => $paste,
            'title' => $paste->title,
            'content' => $content,
            'createdAt' => $paste->created_at,
            'updatedAt' => $paste->updated_at
        ]);
    }

    public function createPaste(Request $request)
    {
        if (RateLimiter::tooManyAttempts("paste-create:" . $request->ip(), $perMinute = 4)) {
            $seconds = RateLimiter::availableIn('paste-create:' . $request->ip());

            return redirect('/paste')
                ->withInput($request->input())
                ->withErrors(['ratelimit' => "You are too fast! You may create a new paste in $seconds seconds."]);
        }

        $user = $request->user();
        if ($user == null) $user = null;
        else $user = $user->id;

        $request->validate([
            'title' => 'max:50',
            'lifetime' => 'string|max:10',
            'visibility' => 'string|max:20',
            'style' => 'string|max:20',
            'content' => 'string|max:500000|required|min:20'
        ]);

        $title = $request->get('title', 'Unknown Paste');
        if ($title == null) {
            $title = 'Unknown Paste';
        }

        $lifetime = $request->get('lifetime', '3d');
        if ($user == null) {
            $lifetime = '3d';
        }

        if ($lifetime != null) {
            $lifetime = match ($lifetime) {
                '1w' => Carbon::now()->addWeeks(),
                '1m' => Carbon::now()->addMonths(),
                '1y' => Carbon::now()->addYears(),
                '-1' => null,
                default => Carbon::now()->addMinutes(2), // todo change to 3 days
            };
        }

        $visibility = $request->get('visibility', 'PUBLIC');
        if ($user == null || !in_array($visibility, ['PUBLIC', 'HIDDEN', 'PRIVATE'])) {
            $visibility = 'PUBLIC';
        }

        $style = $request->get('style');
        if ($style == "none") {
            $style = null;
        }

        $content = $request->get('content');

        do {
            $id = WebUtils::generateRandomString();
        } while (Paste::query()->where('id', $id)->exists());

        $fileName = "/home/pastes/$id.txt.gz";

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $fileStream = gzopen($fileName, "wb9");
        gzwrite($fileStream, $content);
        gzclose($fileStream);

        $paste = new Paste([
            'id' => $id,
            'title' => $title,
            'creator' => $user,
            'style' => $style,
            'visibility' => $visibility,
            'expire_at' => $lifetime,
        ]);

        $paste->save();
        return redirect("/paste/$id?copy=true");
    }

}
