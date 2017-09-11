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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $payload;
    public function __construct($payload)
    {
        $this->payload = $payload;
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
        \Log::info('command..' . $this->payload->ref);
    }
}
