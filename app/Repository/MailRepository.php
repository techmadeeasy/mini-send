<?php


namespace App\Repository;


use App\Models\Recipient;
use App\Models\Sender;

class MailRepository
{
    public function findSender($email_address){
        $sender = Sender::firstOrCreate([
            "email"=>$email_address,
        ]);

        return $sender;
    }

    public function findRecipient($email_address){
        $recipient = Recipient::firstOrCreate([
            "email"=>$email_address
        ]);

        return $recipient;
    }

    public function saveMail($email_content, $recipient, $sender, $img){
        $imgPath = $this->saveImg($img);

        $save = \App\Models\Mail::create([
            'is_sent'=>false,
            "subject"=>$email_content['subject'],
            "text"=>$email_content['text'],
            "html_content"=>$email_content['htmlContent'],
            "recipient_id"=>$recipient->id,
            "sender_id"=>$sender->id,
            'file_path'=> $imgPath
        ]);

        return $save;
    }

    protected function saveImg($img){
        $path = 'attachments/'. $img->getClientOriginalName();
        $filename =  $img->storeAs('attachments', $img->getClientOriginalName());
        return $path;
    }
}
