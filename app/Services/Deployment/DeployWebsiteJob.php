<?php
namespace App\Services\Deployment;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeployWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    protected $generalCommands = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
    ];
    protected $npmCommands = [
        'npm run production',
    ];
    protected $npmRequiredFolders = [
        'resources/assets/*'
    ];

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        if(app()->environment() === 'local'){
            abort('cant deploy website on local');
        }

        chdir(base_path());

        \Log::info('Starting executing commands.');
        $this->runCommands();
        \Log::info('All commands executed.');
        $this->sendSlackNotification();
        \Log::info('Slack Notification delivered.');
    }
    private function runCommands()
    {
        foreach($this->generalCommands as $command) {
            \Log::info('command => ' . $command . ' starting...');
            shell_exec($command);
            \Log::info('command => ' . $command . ' done.');
        }

        if($this->needRunNpm()){
            \Log::info('* * * npm run required * * *');
            foreach($this->npmCommands as $command) {
                \Log::info('command => '. $command . ' starting...');
                shell_exec($command);
                \Log::info('command => '. $command . ' done.');
            }
        }
    }
    private function sendSlackNotification()
    {
        (new Deployment)->notify(new WebsiteDeployed($this->payload));
    }
    private function needRunNpm()
    {
        foreach($this->payload->commits as $commit){
            foreach($commit->modified as $modifiedFile){
                foreach($this->npmRequiredFolders as $npmRequiredFolder){
                    if(str_is($npmRequiredFolder,$modifiedFile)){
                        return true;
                    }
                }
            }
        }
        return false;
    }
}