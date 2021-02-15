<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Recipient;
use App\Models\Sender;
use App\Repository\MailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    protected  $mail;
    public function __construct(MailRepository $mail){
        $this->mail = $mail;
    }
    public function sendEmail(Request $request){
        $validate = $request->validate([
            "from"=>"required|email",
            "to"=>"required|email",
            "subject"=>"required",
            "text"=>"max:1000",
            "htmlContent"=>"string",
            "file"=>"file"
        ]);
        $validate['file'] = 'attachments/'. $request->file('file')->getClientOriginalName();
        $sender = $this->mail->findSender($validate['from']);
        $recipient = $this->mail->findRecipient($validate['to']);
        $this->mail->saveMail($validate, $recipient, $sender, $request->file('file'));
        $send = Mail::to($request->to)->queue(new Email($validate));
        return response()->json('true');
    }

    public function getEmails(){
        $emails = \App\Models\Mail::all();
        foreach ($emails as $email){
          $recipient =  $email->recipient;
          $sender = $email->sender;
        }
        return $emails;
    }

    public function getRecipientEmails($recipient_id){
        $emails = Recipient::findorFail($recipient_id)->emails;
        foreach ($emails as $email){
            $recipient =  $email->recipient;
            $sender = $email->sender;
        }
        return $emails;
    }

    public function getEmail($id){
        $email =  \App\Models\Mail::findorFail($id);
        $email->sender;
        $email->recipient;
        return $email;
    }
}
