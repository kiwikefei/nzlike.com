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
//        $pusher = $this->payload->head_commit->committer;
//        $receiver = $pusher->name;
//        $changes = $this->payload->compare;
//        $commits = $this->payload->commits;
//        $website = app()->name();
//        $fields = [];
//        foreach ($commits as $commit){
//            $committer = $commit->committer;
//            $message = $commit->message;
//            $url = $commit->url;
//            $fields["<{$url}|{$message}>"] = "{$committer}";
//        }
//        $deployment = compact('website','pusher','receiver','changes','fields');

        return (new SlackMessage)
            ->success()
            ->content($this->payload)
//            ->attachment(function($attachment) use( $deployment) {
            ->attachment(function($attachment) {
                $attachment->title("New delivery", url('test/1'))
                    ->fields([
                        'Hey'   => 'Yo.',
                    ]);
            });

    }
}