<?php

namespace App\Http\Controllers;

use App\Models\Plugins\Plugin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class BBCodeController extends Controller
{

    public function parse(Request $request)
    {
        $data = $request->input('data', '');

        return View::make('bbcode')
            ->with('data', $data);
    }

}
