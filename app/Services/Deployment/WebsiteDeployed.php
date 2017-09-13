<?php

namespace App\Services\Deployment;

use Carbon\Carbon;
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
        $pusher = $this->payload->head_commit->committer;
        $changes = $this->payload->compare;
        $commits = $this->payload->commits;
        $timestamp = $this->payload->head_commit->timestamp;
        $website = config('app.name');
        $fields = [];
        $i = 0;
        foreach ($commits as $commit){
            $i ++;
            $message = $commit->message;
            $url = $commit->url;
            $commitTime = Carbon::parse($commit->timestamp)->toDateTimeString();

            $fields["Commit {$i} ({$commitTime})"] = "<{$url}|{$message}>";
        }
        $deployment = compact('website','pusher','changes','fields','timestamp');
//s aa
        return (new SlackMessage)
            ->success()
            ->content("New delivery for [{$website}] processed. by: {$pusher->name}($pusher->email)")
            ->attachment(function($attachment) use( $deployment) {
                $attachment->title("Click to see changes", $deployment['changes'])
                    ->fields($deployment['fields'])
                    ->footer('github')
                    ->timestamp($deployment['timestamp']);
            });

    }
}
