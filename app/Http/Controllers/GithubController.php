<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function callback(Request $request)
    {
        $payload = $request->payload;
        echo($payload->ref);
    }
}
