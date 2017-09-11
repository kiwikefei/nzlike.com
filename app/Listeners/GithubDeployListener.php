<?php

namespace App\Listeners;

use App\Jobs\DeploySite;
use App\Events\GithubPushed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GithubDeployListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GithubPushed  $event
     * @return void
     */
    public function handle(GithubPushed $event)
    {
        DeploySite::dispatch($event->payload);
//        \Artisan::command('web:deploy {payload}', $event->payload);
    }
}
