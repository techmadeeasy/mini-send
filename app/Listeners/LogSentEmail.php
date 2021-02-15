<?php

namespace App\Listeners;

use App\Models\Recipient;
use App\Models\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSentEmail
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $sender = Sender::firstOrCreate([
            "email"=>$validate['from']
        ]);

        $recipient = Recipient::firstOrCreate([
            "email"=>$validate['to']
        ]);

        $save = \App\Models\Mail::create([
            'is_sent'=>false,
            "subject"=>$validate['subject'],
            "text"=>$validate['text'],
            "html_content"=>$validate['htmlContent'],
            "recipient_id"=>$recipient->id,
            "sender_id"=>$sender->id,
        ]);
    }
}
