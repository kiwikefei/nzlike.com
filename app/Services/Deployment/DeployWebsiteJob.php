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

    protected $generalCommends = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
        'php artisan migrate',
    ];
    protected $npmCommands = [
        'npm run production',
    ];
    protected $npmFolders = [
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
        foreach($this->generalCommends as $command) {
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
        \Log::info('All commands executed.');
    }
    private function needRunNpm()
    {
        foreach($this->payload->commits as $commit){
            foreach($commit->modified as $file){
                foreach($this->npmFolders as $npmFolder){
                    if(str_is($npmFolder,$file)){
                        return true;
                    }
                }
            }
        }
        return false;
    }
}