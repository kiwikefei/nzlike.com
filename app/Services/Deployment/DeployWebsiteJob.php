<?php
namespace App\Services\Deployment;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeployWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    protected $commends = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
        'php artisan migrate',
        'npm run production ',
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

        Log::info('Starting executing commands.');
        foreach($this->commends as $command) {
            Log::info('command => ' . $command . ' starting')
            shell_exec($command);
            Log::info('command => ' . $command . ' done.');
        }
        Log::info('All commands executed.');
        return true;
    }
}