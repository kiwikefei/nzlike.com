<?php

namespace App\Services\Deployment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deployment extends Model
{
    use Notifiable;

    public function routeNotificationForSlack()
    {
        return config('slack.webhook');
    }
}
