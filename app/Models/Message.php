<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $fillable = ['chat_id', 'user_id', 'message', 'is_read'];
    //Un mensaje pertence a un chat especifico
    public function chat(){
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    //Un mensaje es enviado por un usuario en especifico
    public function sender(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
