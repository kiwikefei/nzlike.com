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
        $pusher = $this->payload->head_commit->committer->name;
        $changes = $this->payload->compare;
        $commits = $this->payload->commits;
        $website = config('app.name');

        $fields = [];
        foreach ($commits as $commit){
            $committer = $commit->committer;
            $message = $commit->message;
            $url = $commit->url;
            $fields["commit"] = "<{$url}|{$message}>";
        }
        $deployment = compact('website','pusher','changes','fields');
//s
        return (new SlackMessage)
            ->success()
            ->content("New delivery for [{$website}] processed. by:{$pusher}")
            ->attachment(function($attachment) use( $deployment) {
                $attachment->title("Click to see changes", $deployment['changes'])
                    ->fields($deployment['fields']);
            });

    }
}
