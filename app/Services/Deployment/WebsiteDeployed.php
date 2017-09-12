<?php

namespace App\Services\Deployment;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class WebsiteDeployed extends Notification
{
    use Queueable;
    private $payload;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content($this->payload)
            ->attachment(function($attachment){
                $attachment->title('Deployment', url('deployment/1'))
                    ->fields([
                        'Hey'   => 'Yo.',
                    ]);
            });

    }
}
