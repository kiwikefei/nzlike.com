<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function callback(Request $request)
    {
        $githubEvent = $request->header('X-GitHub-Event');
        $githubSignature =  $request->header('X-Hub-Signature');
        $githubContent = $request->getContent();
        $githubSignatureCheck =  'sha1=' . hash_hmac('sha1', $githubContent, 'secret');
        $payload = json_decode($request->getContent());

        echo ("{$githubEvent}\n");
        echo ("{$payload->ref}\n");
        
        \Log::info($githubEvent);
        \Log::info($payload->ref);
        \Log::info($githubSignature);
        \Log::info($githubSignatureCheck);
    }
}
