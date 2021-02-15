<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sender(){
        return $this->belongsTo(Sender::class);
    }

    public function recipient(){
        return $this->belongsTo(Recipient::class);
    }
}
