<?php

namespace App\Listeners;

use App\Events\GithubPushed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GithubDeployListener
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
        \Log::info('listener...' . $event->payload->ref);
        \Artisan::command('web:deploy {payload}', $event->payload);
    }
}
