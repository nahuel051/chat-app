<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = ['user_one_id','user_two_id'];

    //Primer usuario participa de un chat
    public function userOne(){
        return $this->belongsTo(User::class, 'user_one_id');
    }

    //Segundo usuario participa de un chat

    public function userTwo(){
        return $this->belongsTo(User::class, 'user_two_id');
    }

    //Un chat puede tener muchos mensajes
    public function messages(){
        return $this->hasMany(Message::class);
    }
}
