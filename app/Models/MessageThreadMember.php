<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadMember extends Model
{
    use HasFactory;

    protected $guarded = ['message_thread_id'];

    public function messageThread()
    {
        return $this->belongsTo('App\Models\MessageThread');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
