<?php
class Deployer
{
    protected $server;
    protected $event;
    protected $payload;
    protected $output;
    protected $slackHook = 'https://hooks.slack.com/services/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';
    protected $slackTeam = 'https://kiwistyle.slack.com/team/';
    protected $slackChannel = '#deployment';
    protected $slackBot = 'DeployBot';
    protected $commentsPipeline = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
        'php artisan migrate',
//        'npm run production'
    ];
    // test
    // commit 1
    // commit 2
    public function __construct($server, $event)
    {
        $this->server = $server;
        $this->event = $event;
        $this->payload = json_decode( file_get_contents('php://input') );
    }
    protected function isPost()
    {
        return !strcasecmp('post', $_SERVER['REQUEST_METHOD']);
    }
    protected function isTriggered()
    {
        return  ($this->isPost()) &&
                ($this->server == $_SERVER['HTTP_HOST']) &&
                ($this->payload) &&
                ($this->payload->ref == $this->event);
    }
    public function run()
    {
        if (!$this->isTriggered()) {
            return false;
        }

        chdir(realpath(__DIR__ . '/../'));
        $stagingEnv = getcwd() . '/.env';

        if (!$stagingEnv){
            return false;
        }
        $output = '';
        foreach($this->commentsPipeline as $command) {
            shell_exec($command);
            $output .= $command . " is done \n";
        }
//        echo ($output);
        $link = "https://github.com/kiwikefei/nzlike.com/commit/{$_SERVER['HTTP_X_GITHUB_DELIVERY']}";
        $pusher = $this->payload->head_commit->committer;
        $commitMessage = $this->notifyTo($pusher->name) . "\n"
            . " New delivery for [{$this->server}] , pushed by [{$pusher->name}] \n"
            . " <{$pusher->compare}|review code>\n";

        foreach($this->payload->commits as $commit) {
            $committer = $commit->committer;
            $commitMessage .= "        <$commit->url|[{$commit->message}]> by: [{$committer->name}] \n";
        }
        $message = $this->sendSlackNotification($commitMessage);
        echo $output . $message;
    }

    protected function sendSlackNotification($message)
    {
        $payload = [
            'channel' => '#' . ltrim($this->slackChannel, '#'),
            'text' => urlencode($message),
            'username' => $this->slackBot
        ];
        $payload = 'payload=' . json_encode($payload);
        $ch = curl_init($this->slackHook);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if ($result === false) {
            $message .= 'Curl error' . curl_error($ch) . "\n";
        }
        else {
            $message .= "Slack notification status: {$result} \n";
        }
        curl_close($ch);
        return $message;
    }
    protected function htmlReplace($string) {
        // https://api.slack.com/docs/message-formatting#how_to_escape_characters
        // Also use backslashes to escape double quotes/backslashes themselves,
        // that would otherwise break the JSON.
        // (deal with the slashes before the quotes otherwise the escaped quotes would be re-escaped!)
        // test auto deployment.
        return str_replace(array('&', '<', '>', '\\', '"'), array('&amp;', '&lt;', '&gt;', '\\\\', '\"'), $string);
    }
    protected function notifyTo($gitHubName)
    {
        $nameMap = [
            'Andy Tang' => '@andy',
        ];
        if (isset($nameMap[$gitHubName])) {
            return '<' . $this->slackTeam . '|' . $nameMap[$gitHubName] . '>';
        }
        return '@all';
    }
}
$deployer = new Deployer('nzlike.com', 'refs/heads/master');
$deployer->run();