<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $table = 'chats';
    protected $guarded = ['id'];

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class,'chat_id'); 
    }
}