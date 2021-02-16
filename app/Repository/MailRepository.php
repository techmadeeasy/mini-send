<?php


namespace App\Repository;


use App\Mail\Email;
use App\Models\Recipient;
use App\Models\Sender;
use Illuminate\Support\Facades\Mail;

class MailRepository
{
    public $attachmentDirectory = "attachments/";
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

    public function saveMail($email_content, $recipient, $sender, $filePath){

        $save = \App\Models\Mail::create([
            'is_sent'=>false,
            "subject"=>$email_content['subject'],
            "text"=>$email_content['text'],
            "html_content"=>$email_content['htmlContent'],
            "recipient_id"=>$recipient->id,
            "sender_id"=>$sender->id,
            'file_path'=> $filePath
        ]);

        return $save;
    }

    protected function saveImg($file){
        $filename =  $file->storeAs($this->attachmentDirectory, $file->getClientOriginalName());
        return $this->attachmentDirectory . $file->getClientOriginalName();
    }

    public function sendEmailProcess($validated_data, $req){
        $sender = $this->findSender($validated_data['from']);
        $recipient = $this->findRecipient($validated_data['to']);
        $validated_data['file'] = (($req->hasFile('file')) ? $this->saveImg($req->file('file')) : null);
        $validated_data['email_id'] = $this->saveMail($validated_data, $recipient, $sender, $validated_data['file'])->id;
        $send = Mail::to($validated_data['to'])->queue(new Email($validated_data));
        return true;
    }
}
