<?php

namespace App\Http\Controllers;

use App\Events\GithubPushed;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function callback(Request $request)
    {
        $githubEvent = $request->header('X-GitHub-Event');

        if($githubEvent != 'push'){
            $message = "push event not received.";
            \Log::info($message);
            return response()->json(['message'  => $message], 403);
        }
        $githubContent = $request->getContent();
        $githubWebhookSecret = config('deploy.github.webhook_key');
        \Log::info($githubWebhookSecret);
        if($githubWebhookSecret){
            $githubSignature =  $request->header('X-Hub-Signature');
            $githubSignatureCheck =  'sha1=' . hash_hmac('sha1', $githubContent, 'secret');
            if(hash_equals($githubSignature,$githubSignatureCheck)){
                $message = "github signature doesn't match.";
                \Log::info($message);
                return response()->json(['message'  => $message], 403);
            }
        }

        $payload = json_decode($request->getContent());

        if( $payload->ref != config('deploy.github.branch')) {
            $message = "github branch doesn't allowed.";
            \Log::info($message);
            return response()->json([
                'message' => $message
            ], 404);
        }
        event()->fire(new GithubPushed($payload));
        \Log::info(shell_exec('ls -la'));
        return response()->json([
           'message'    => 'github push processed.'
        ], 200);
    }
}
