<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployWeb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy website';
    protected $commends = [
        'git reset --hard HEAD',
        'git checkout master',
        'git pull origin master',
        'php artisan migrate',
        'npm run production  2>&1',
    ];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $payload;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(app()->environment() === 'local'){
            return $this->error('cant deploy website on local');
        }
        chdir(base_path());
        foreach($this->comments as $command) {
            shell_exec($command);
            \Log::info('command..' . $command . 'is done');
        }
        return true;

    }
}
