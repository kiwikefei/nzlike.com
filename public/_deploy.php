<?php
class Deployer
{
    protected $server;
    protected $event;
    protected $payload;
    protected $output;
    protected $commentsPipeline = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
    ];
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
        echo ('path=' . realpath(__DIR__ . '/../') . "\n");
        $output = '';
        foreach($this->commentsPipeline as $command) {
            shell_exec($command);
            $output .= $command . " is done \n";
        }
        echo ($output);
        // $this->payload->head_commit->committer->name
        // $_SERVER['HTTP_X_GITHUB_DELIVERY']
        // $this->server
//        $commitMessage = $this->notifyTo($this->payload->head_commit->committer->name) . "\n"
//
//            . "The delivery (Ref: {$_SERVER['HTTP_X_GITHUB_DELIVERY']})"
//            . " is pushing the following commits to {$this->server}"
//            . " \n---------------------------------------------\n";
//        foreach($this->payload->commits as $commit) {
//            $committer = $commit->committer->name != $this->payload->head_commit->committer->name ? '(committed by' . $commit->committer->name . ')' : '';
//            $commitMessage .= "{$commit->message} {$committer} \n";
//        }
//        $message = $this->sendSlackNotification($commitMessage);
//        echo $output . $message;
    }
}
$deployer = new Deployer('nzlike.com', 'refs/heads/master');
$deployer->run();