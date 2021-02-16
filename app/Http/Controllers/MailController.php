<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Recipient;
use App\Models\Sender;
use App\Repository\MailRepository;
use Illuminate\Database\Eloquent\Builder;
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
        $file = $request->hasFile('file') ? $request->file('file') : null;
        $this->mail->sendEmailProcess($validate, $request);
        return response()->json('true');
    }

    public function getEmails(){
        $emails = \App\Models\Mail::all();
        foreach ($emails as $email){
          $email->recipient;
          $email->sender;
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

    public function search($search){
        $result = \App\Models\Mail::where('subject', 'like', "%$search%")->orWhereHas('recipient', function (Builder $query) use ($search){
                $query->where('email', 'like', "%$search%");
        })->orWhereHas('sender', function (Builder $query) use ($search){
            $query->where('email', 'like', "%$search%");
        })->get();
        return $result;
    }

    public function getAllRecipient(){
        return Recipient::all()->lazy();
    }
}
