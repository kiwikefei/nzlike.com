<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function callback(Request $request)
    {
        $xEvent = $request->header('X-GitHub-Event');
        $payload = json_decode($request->getContent());
        echo ("{$xEvent}\n");
        echo ("{$payload->ref}\n");
        \Log::info($xEvent);
        \Log::info($payload->ref);
    }
}
